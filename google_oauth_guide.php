<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hướng dẫn cài đặt Google OAuth</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
        }
        .container {
            max-width: 900px;
        }
        .step {
            margin-bottom: 30px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .step h3 {
            color: #4CAF50;
            margin-bottom: 15px;
        }
        img {
            max-width: 100%;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 15px 0;
        }
        code {
            background-color: #f1f1f1;
            padding: 3px 5px;
            border-radius: 3px;
            font-family: Consolas, Monaco, 'Andale Mono', monospace;
        }
        .code-block {
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            font-family: Consolas, Monaco, 'Andale Mono', monospace;
            margin: 15px 0;
        }
        .note {
            background-color: #fff3cd;
            padding: 15px;
            border-left: 4px solid #ffc107;
            margin: 15px 0;
        }
        .error {
            background-color: #f8d7da;
            padding: 15px;
            border-left: 4px solid #dc3545;
            margin: 15px 0;
        }
        .success {
            background-color: #d4edda;
            padding: 15px;
            border-left: 4px solid #28a745;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mt-4 mb-4">Hướng dẫn cài đặt Google OAuth</h1>
        
        <div class="step">
            <h3>Bước 1: Truy cập Google Cloud Console</h3>
            <ol>
                <li>Truy cập <a href="https://console.cloud.google.com/" target="_blank">https://console.cloud.google.com/</a></li>
                <li>Đăng nhập bằng tài khoản Google của bạn</li>
            </ol>
        </div>
        
        <div class="step">
            <h3>Bước 2: Tạo dự án mới</h3>
            <ol>
                <li>Nhấp vào menu thả xuống ở trên cùng bên trái (có tên dự án hiện tại)</li>
                <li>Nhấp "Dự án mới" (New Project)</li>
                <li>Đặt tên cho dự án (ví dụ: "PlantShop OAuth")</li>
                <li>Nhấp "Tạo" (Create)</li>
            </ol>
        </div>
        
        <div class="step">
            <h3>Bước 3: Kích hoạt Google OAuth API</h3>
            <ol>
                <li>Trong bảng điều khiển, chọn dự án vừa tạo</li>
                <li>Trong menu bên trái, nhấp "APIs & Services" > "Library"</li>
                <li>Tìm kiếm "Google Identity" hoặc "Google OAuth2 API"</li>
                <li>Chọn API và nhấn "Enable" (Kích hoạt)</li>
            </ol>
        </div>
        
        <div class="step">
            <h3>Bước 4: Cấu hình Consent Screen (Màn hình chấp thuận)</h3>
            <ol>
                <li>Trong menu bên trái, nhấp "APIs & Services" > "OAuth consent screen"</li>
                <li>Chọn loại người dùng (User Type): "External" (hoặc "Internal" nếu bạn đang sử dụng Google Workspace)</li>
                <li>Nhấp "Create" (Tạo)</li>
                <li>Điền thông tin bắt buộc:
                    <ul>
                        <li>App name (Tên ứng dụng): "PlantShop"</li>
                        <li>User support email (Email hỗ trợ): email của bạn</li>
                        <li>Developer contact information (Thông tin liên hệ nhà phát triển): email của bạn</li>
                    </ul>
                </li>
                <li>Nhấp "Save and Continue" (Lưu và Tiếp tục)</li>
                <li>Trong phần "Scopes", nhấp "Add or Remove Scopes" và chọn các quyền:
                    <ul>
                        <li>./auth/userinfo.email</li>
                        <li>./auth/userinfo.profile</li>
                    </ul>
                </li>
                <li>Nhấp "Save and Continue" (Lưu và Tiếp tục)</li>
                <li>Thêm test users nếu cần, sau đó "Save and Continue" (Lưu và Tiếp tục)</li>
                <li>Xem lại thông tin và nhấp "Back to Dashboard" (Quay lại Dashboard)</li>
            </ol>
        </div>
        
        <div class="step">
            <h3>Bước 5: Tạo Client ID và Client Secret</h3>
            <ol>
                <li>Trong menu bên trái, nhấp "APIs & Services" > "Credentials"</li>
                <li>Nhấp "+ Create Credentials" > "OAuth client ID"</li>
                <li>Chọn Application type (Loại ứng dụng): "Web application"</li>
                <li>Đặt Name (Tên): "PlantShop Web Client"</li>
                <li>Trong phần "Authorized JavaScript origins", nhấp "ADD URI" và thêm:
                    <div class="code-block">http://localhost</div>
                </li>
                <li>Trong phần "Authorized redirect URIs", nhấp "ADD URI" và thêm:
                    <div class="code-block">http://localhost/LVTN_PhanBon/google_callback.php</div>
                </li>
                <li>Nhấp "Create" (Tạo)</li>
                <li>Copy lại Client ID và Client Secret được tạo</li>
            </ol>
        </div>
        
        <div class="step">
            <h3>Bước 6: Cập nhật file cấu hình</h3>
            <ol>
                <li>Mở file <code>google_config.php</code> trong dự án của bạn</li>
                <li>Thay thế các giá trị giả với Client ID và Client Secret thật:
                    <div class="code-block">
// Cấu hình Google OAuth<br>
$google_client_id = 'YOUR_CLIENT_ID_HERE'; // Thay thế với Client ID của bạn<br>
$google_client_secret = 'YOUR_CLIENT_SECRET_HERE'; // Thay thế với Client Secret của bạn<br>
$google_redirect_url = 'http://localhost/LVTN_PhanBon/google_callback.php'; // URL callback
                    </div>
                </li>
                <li>Lưu file</li>
            </ol>
        </div>
        
        <div class="step">
            <h3>Các lỗi thường gặp</h3>
            
            <div class="error">
                <h4>"The OAuth client was not found."</h4>
                <p><strong>Nguyên nhân:</strong> Client ID không chính xác hoặc chưa được cấu hình trong Google Cloud Console.</p>
                <p><strong>Giải pháp:</strong></p>
                <ul>
                    <li>Kiểm tra lại Client ID trong file google_config.php</li>
                    <li>Đảm bảo rằng dự án trong Google Cloud Console đã được kích hoạt</li>
                </ul>
            </div>
            
            <div class="error">
                <h4>"Error 401: invalid_client"</h4>
                <p><strong>Nguyên nhân:</strong> Client ID hoặc Client Secret không hợp lệ.</p>
                <p><strong>Giải pháp:</strong></p>
                <ul>
                    <li>Kiểm tra và cập nhật lại Client ID và Client Secret</li>
                    <li>Đảm bảo không có khoảng trắng thừa khi copy-paste</li>
                </ul>
            </div>
            
            <div class="error">
                <h4>"redirect_uri_mismatch"</h4>
                <p><strong>Nguyên nhân:</strong> URL callback trong code không khớp với URL được cấu hình trong Google Cloud Console.</p>
                <p><strong>Giải pháp:</strong></p>
                <ul>
                    <li>Đảm bảo URL trong biến $google_redirect_url khớp chính xác với URL đã đăng ký trong phần "Authorized redirect URIs"</li>
                    <li>URL phải khớp chính xác cả về protocol (http/https), domain, đường dẫn và cả chữ hoa/thường</li>
                </ul>
            </div>
            
            <div class="error">
                <h4>"This app isn't verified"</h4>
                <p><strong>Nguyên nhân:</strong> Ứng dụng OAuth chưa được xác minh bởi Google.</p>
                <p><strong>Giải pháp:</strong></p>
                <ul>
                    <li>Trong quá trình phát triển, bạn có thể nhấp vào "Advanced" và "Go to [App Name] (unsafe)" để tiếp tục</li>
                    <li>Khi triển khai thực tế, bạn cần hoàn thiện quy trình xác minh của Google</li>
                </ul>
            </div>
        </div>
        
        <div class="step">
            <h3>Thử nghiệm</h3>
            <p>Sau khi cấu hình xong, bạn có thể tiến hành thử nghiệm:</p>
            <ol>
                <li>Truy cập trang đăng nhập (login.php)</li>
                <li>Nhấp vào nút "Đăng nhập với Google"</li>
                <li>Nếu mọi thứ được cấu hình đúng, bạn sẽ thấy màn hình đăng nhập Google</li>
                <li>Đăng nhập bằng tài khoản Google của bạn</li>
                <li>Sau khi đăng nhập, bạn sẽ được chuyển hướng trở lại trang web của mình</li>
            </ol>
            
            <div class="note">
                <p><strong>Lưu ý:</strong> Nếu bạn gặp lỗi, hãy kiểm tra thông tin trong file <code>google_callback.php</code> bằng cách bật chế độ debug (<code>$debug_mode = true;</code>). Điều này sẽ hiển thị thông tin chi tiết về lỗi.</p>
            </div>
        </div>
        
        <div class="text-center mt-4 mb-5">
            <a href="login.php" class="btn btn-primary">Quay lại trang đăng nhập</a>
        </div>
    </div>
</body>
</html> 