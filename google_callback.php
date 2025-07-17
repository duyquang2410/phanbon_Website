<?php
session_start();
require_once 'connect.php';
require_once 'google_config.php';

// Chế độ debug - Bật khi cần kiểm tra lỗi
$debug_mode = true;

// Hàm hiển thị thông tin debug
function debugInfo($message, $data = null) {
    global $debug_mode;
    if ($debug_mode) {
        echo "<pre>";
        echo "<h3>Debug Info:</h3>";
        echo "<p>$message</p>";
        if ($data !== null) {
            echo "<h4>Data:</h4>";
            print_r($data);
        }
        echo "</pre>";
    }
}

// Kiểm tra code từ Google
if(isset($_GET['code'])) {
    $code = $_GET['code'];
    $token_url = 'https://accounts.google.com/o/oauth2/token';
    
    // Chuẩn bị dữ liệu để lấy token
    $token_data = array(
        'code' => $code,
        'client_id' => $google_client_id,
        'client_secret' => $google_client_secret,
        'redirect_uri' => $google_redirect_url,
        'grant_type' => 'authorization_code'
    );
    
    // Debug dữ liệu token request
    debugInfo("Token Request Data:", $token_data);
    
    // Khởi tạo cURL
    $curl = curl_init();
    
    // Thiết lập các tùy chọn cho cURL
    curl_setopt($curl, CURLOPT_URL, $token_url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($token_data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
    // Thực hiện cURL để lấy access token
    $token_response = curl_exec($curl);
    $curl_error = curl_error($curl);
    curl_close($curl);
    
    // Debug kết quả token
    if (!empty($curl_error)) {
        debugInfo("cURL Error:", $curl_error);
    }
    
    // Giải mã kết quả JSON
    $token_data = json_decode($token_response, true);
    debugInfo("Token Response:", $token_data);
    
    if(isset($token_data['access_token'])) {
        // Lấy thông tin người dùng
        $access_token = $token_data['access_token'];
        $user_info_url = 'https://www.googleapis.com/oauth2/v2/userinfo?access_token=' . $access_token;
        
        // Khởi tạo cURL mới để lấy thông tin người dùng
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $user_info_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
        $user_info_response = curl_exec($curl);
        $curl_error = curl_error($curl);
        curl_close($curl);
        
        if (!empty($curl_error)) {
            debugInfo("cURL Error (User Info):", $curl_error);
        }
        
        $user_info = json_decode($user_info_response, true);
        debugInfo("User Info Response:", $user_info);
        
        if(isset($user_info['id'])) {
            // Kiểm tra xem người dùng đã đăng nhập bằng Google trước đó chưa
            $google_id = $user_info['id'];
            $email = $user_info['email'];
            $name = $user_info['name'];
            $picture = $user_info['picture'];
            
            // Kiểm tra xem user đã tồn tại trong database chưa
            $check_sql = "SELECT * FROM khach_hang WHERE KH_GOOGLEID = ? OR KH_EMAIL = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("ss", $google_id, $email);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            
            if($check_result->num_rows > 0) {
                // Người dùng đã tồn tại, cập nhật thông tin nếu cần
                $user = $check_result->fetch_assoc();
                
                // Cập nhật Google ID nếu chưa có
                if(empty($user['KH_GOOGLEID'])) {
                    $update_sql = "UPDATE khach_hang SET KH_GOOGLEID = ? WHERE KH_MA = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->bind_param("si", $google_id, $user['KH_MA']);
                    $update_stmt->execute();
                    $update_stmt->close();
                }
                
                // Đăng nhập
                $_SESSION['user_id'] = $user['KH_MA'];
                $_SESSION['user_name'] = $user['KH_TEN'];
                $_SESSION['user_avatar'] = $user['KH_AVATAR'];
                
                header("Location: /web/phanbon_Website/index.php");
                exit();
            } else {
                // Tạo người dùng mới
                $current_date = date("Y-m-d");
                $username = 'google_' . time(); // Tạo tên đăng nhập ngẫu nhiên
                $password = md5(time() . $google_id); // Tạo mật khẩu ngẫu nhiên
                
                // Lưu ảnh avatar từ Google (nếu cần)
                $avatar = 'default-avatar.jpg';
                
                // Thêm người dùng mới vào database
                $insert_sql = "INSERT INTO khach_hang (KH_TEN, KH_EMAIL, KH_GOOGLEID, KH_TENDANGNHAP, KH_MATKHAU, KH_AVATAR, KH_NGAYDK) 
                              VALUES (?, ?, ?, ?, ?, ?, ?)";
                $insert_stmt = $conn->prepare($insert_sql);
                $insert_stmt->bind_param("sssssss", $name, $email, $google_id, $username, $password, $avatar, $current_date);
                
                if($insert_stmt->execute()) {
                    $user_id = $insert_stmt->insert_id;
                    
                    // Đăng nhập người dùng mới
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['user_name'] = $name;
                    $_SESSION['user_avatar'] = $avatar;
                    
                    header("Location: /web/phanbon_Website/index.php");
                    exit();
                } else {
                    // Lỗi khi tạo người dùng mới
                    $_SESSION['login_error'] = "Lỗi khi tạo tài khoản: " . $conn->error;
                    header("Location: login.php");
                    exit();
                }
                
                $insert_stmt->close();
            }
            
            $check_stmt->close();
        } else {
            // Không lấy được thông tin người dùng
            debugInfo("Error: Không thể lấy thông tin người dùng từ Google:", $user_info);
            
            if (!$debug_mode) {
                $_SESSION['login_error'] = "Không thể lấy thông tin người dùng từ Google.";
                header("Location: login.php");
                exit();
            }
        }
    } else {
        // Không lấy được access token
        debugInfo("Error: Không thể xác thực với Google. Response:", $token_data);
        
        if (!$debug_mode) {
            $_SESSION['login_error'] = "Không thể xác thực với Google. Lỗi: " . ($token_data['error'] ?? 'Unknown error');
            header("Location: login.php");
            exit();
        }
    }
} else {
    if (isset($_GET['error'])) {
        debugInfo("Google OAuth Error:", $_GET);
    }
    
    if (!$debug_mode) {
        // Không có code từ Google
        header("Location: login.php");
        exit();
    } else {
        echo "<h3>Error: Không có code từ Google.</h3>";
        echo "<a href='login.php'>Quay lại trang đăng nhập</a>";
    }
}

if ($debug_mode) {
    echo "<hr>";
    echo "<p><strong>Note:</strong> Bạn đang ở chế độ debug. Tắt chế độ debug (set \$debug_mode = false) khi triển khai.</p>";
    echo "<a href='login.php'>Quay lại trang đăng nhập</a>";
}
?> 