<?php
// File quản lý các chức năng liên quan đến giỏ hàng

function writeLog($message) {
    $log_file = __DIR__ . '/logs/cart.log';
    $timestamp = date('Y-m-d H:i:s');
    $log_message = "[$timestamp] $message\n";
    file_put_contents($log_file, $log_message, FILE_APPEND);
}

/**
 * Tạo giỏ hàng mới cho người dùng
 * @param int $user_id ID của khách hàng
 * @return int ID của giỏ hàng vừa tạo
 */
function createCart($conn, $user_id) {
    writeLog("Creating new cart for user_id: $user_id");
    $sql = "INSERT INTO gio_hang (KH_MA) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $cart_id = $conn->insert_id;
    $stmt->close();
    writeLog("Created cart_id: $cart_id for user_id: $user_id");
    return $cart_id;
}

/**
 * Lấy giỏ hàng hiện tại của người dùng
 * @param int $user_id ID của khách hàng
 * @return int|null ID của giỏ hàng hoặc null nếu không tồn tại
 */
function getCurrentCart($conn, $user_id) {
    writeLog("Getting current cart for user_id: $user_id");
    // Kiểm tra xem người dùng đã có giỏ hàng chưa
    $sql = "SELECT GH_MA FROM gio_hang WHERE KH_MA = ? ORDER BY GH_MA DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $cart_id = $row['GH_MA'];
        writeLog("Found existing cart_id: $cart_id for user_id: $user_id");
    } else {
        // Nếu chưa có giỏ hàng, tạo giỏ hàng mới
        $cart_id = createCart($conn, $user_id);
        writeLog("No existing cart found, created new cart_id: $cart_id");
    }
    
    $stmt->close();
    return $cart_id;
}

/**
 * Thêm sản phẩm vào giỏ hàng
 * @param int $cart_id ID của giỏ hàng
 * @param int $product_id ID của sản phẩm
 * @param float $quantity Số lượng sản phẩm
 * @param string $unit Đơn vị tính
 * @return bool Trạng thái thành công
 */
function addToCart($conn, $cart_id, $product_id, $quantity, $unit = 'cái') {
    try {
        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $check_sql = "SELECT * FROM chitiet_gh WHERE GH_MA = ? AND SP_MA = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ii", $cart_id, $product_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            // Nếu sản phẩm đã có trong giỏ hàng
            $row = $check_result->fetch_assoc();
            
            // Nếu DA_MUA = 1, reset về 0 và cập nhật số lượng mới
            // Nếu DA_MUA = 0, cộng thêm số lượng
            $new_quantity = ($row['DA_MUA'] == 1) ? $quantity : ($row['CTGH_KHOILUONG'] + $quantity);
            
            $update_sql = "UPDATE chitiet_gh SET CTGH_KHOILUONG = ?, DA_MUA = 0 WHERE GH_MA = ? AND SP_MA = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("dii", $new_quantity, $cart_id, $product_id);
            $result = $update_stmt->execute();
            $update_stmt->close();
        } else {
            // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới
            $insert_sql = "INSERT INTO chitiet_gh (GH_MA, SP_MA, CTGH_KHOILUONG, CTGH_DONVITINH, DA_MUA) VALUES (?, ?, ?, ?, 0)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("iids", $cart_id, $product_id, $quantity, $unit);
            $result = $insert_stmt->execute();
            $insert_stmt->close();
        }
        
        $check_stmt->close();
        return $result;
    } catch (Exception $e) {
        error_log("Error in addToCart: " . $e->getMessage());
        throw $e;
    }
}

/**
 * Cập nhật số lượng sản phẩm trong giỏ hàng
 * @param int $cart_id ID của giỏ hàng
 * @param int $product_id ID của sản phẩm
 * @param float $quantity Số lượng mới
 * @return bool Trạng thái thành công
 */
function updateCartItem($conn, $cart_id, $product_id, $quantity) {
    if ($quantity <= 0) {
        // Nếu số lượng <= 0, xóa sản phẩm khỏi giỏ hàng
        return removeCartItem($conn, $cart_id, $product_id);
    }
    
    $sql = "UPDATE chitiet_gh SET CTGH_KHOILUONG = ? WHERE GH_MA = ? AND SP_MA = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("dii", $quantity, $cart_id, $product_id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

/**
 * Xóa sản phẩm khỏi giỏ hàng
 * @param int $cart_id ID của giỏ hàng
 * @param int $product_id ID của sản phẩm
 * @return bool Trạng thái thành công
 */
function removeCartItem($conn, $cart_id, $product_id) {
    $sql = "DELETE FROM chitiet_gh WHERE GH_MA = ? AND SP_MA = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $cart_id, $product_id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

/**
 * Lấy danh sách sản phẩm trong giỏ hàng
 * @param int $cart_id ID của giỏ hàng
 * @return array Mảng chứa thông tin sản phẩm trong giỏ hàng
 */
function getCartItems($conn, $cart_id) {
    writeLog("Getting cart items for cart_id: $cart_id");
    
    // Thêm điều kiện DA_MUA = 0 vào câu truy vấn
    $sql = "SELECT sp.SP_MA, sp.SP_TEN, sp.SP_HINHANH, sp.SP_DONGIA, 
                   c.CTGH_KHOILUONG, c.CTGH_DONVITINH, c.DA_MUA,
                   CASE WHEN sci.SP_MA IS NOT NULL THEN 1 ELSE 0 END as is_selected
            FROM chitiet_gh c
            JOIN san_pham sp ON c.SP_MA = sp.SP_MA
            LEFT JOIN selected_cart_items sci ON c.GH_MA = sci.GH_MA AND c.SP_MA = sci.SP_MA
            WHERE c.GH_MA = ? AND c.DA_MUA = 0
            ORDER BY sp.SP_TEN ASC";
    
    try {
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            writeLog("Error preparing cart items query: " . $conn->error);
            throw new Exception("Lỗi khi chuẩn bị truy vấn giỏ hàng");
        }

        $stmt->bind_param("i", $cart_id);
        if (!$stmt->execute()) {
            writeLog("Error executing cart items query: " . $stmt->error);
            throw new Exception("Lỗi khi thực hiện truy vấn giỏ hàng");
        }

        $result = $stmt->get_result();
        $items = array();
        $total = 0;
        $unique_items_count = 0;
        
        while ($row = $result->fetch_assoc()) {
            $subtotal = $row['SP_DONGIA'] * $row['CTGH_KHOILUONG'];
            $total += $subtotal;
            $unique_items_count++;
            
            $items[$row['SP_MA']] = array(
                'id' => $row['SP_MA'],
                'name' => $row['SP_TEN'],
                'image' => $row['SP_HINHANH'],
                'price' => $row['SP_DONGIA'],
                'quantity' => $row['CTGH_KHOILUONG'],
                'unit' => $row['CTGH_DONVITINH'],
                'subtotal' => $subtotal,
                'is_selected' => $row['is_selected']
            );
        }
        
        $stmt->close();
        writeLog("Found $unique_items_count unique items in cart_id: $cart_id");
        
        return array(
            'items' => $items,
            'total' => $total,
            'count' => $unique_items_count
        );
    } catch (Exception $e) {
        writeLog("Error in getCartItems: " . $e->getMessage());
        throw $e;
    }
}

/**
 * Lấy số lượng sản phẩm trong giỏ hàng
 * @param int $cart_id ID của giỏ hàng
 * @return int Số lượng sản phẩm trong giỏ hàng
 */
function getCartItemCount($conn, $cart_id) {
    $sql = "SELECT COUNT(*) as count FROM chitiet_gh WHERE GH_MA = ? AND DA_MUA = 0";
    try {
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            writeLog("Error preparing cart count query: " . $conn->error);
            throw new Exception("Lỗi khi chuẩn bị truy vấn số lượng giỏ hàng");
        }

        $stmt->bind_param("i", $cart_id);
        if (!$stmt->execute()) {
            writeLog("Error executing cart count query: " . $stmt->error);
            throw new Exception("Lỗi khi thực hiện truy vấn số lượng giỏ hàng");
        }

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return $row['count'];
    } catch (Exception $e) {
        writeLog("Error in getCartItemCount: " . $e->getMessage());
        throw $e;
    }
}

/**
 * Tính tổng tiền giỏ hàng
 * @param int $cart_id ID của giỏ hàng
 * @return float Tổng tiền giỏ hàng
 */
function getCartTotal($conn, $cart_id) {
    $sql = "SELECT SUM(sp.SP_DONGIA * c.CTGH_KHOILUONG) as total 
            FROM chitiet_gh c
            JOIN san_pham sp ON c.SP_MA = sp.SP_MA
            WHERE c.GH_MA = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    
    return $row['total'] ?? 0;
}

/**
 * Xóa toàn bộ giỏ hàng
 * @param int $cart_id ID của giỏ hàng
 * @return bool Trạng thái thành công
 */
function clearCart($conn, $cart_id) {
    $sql = "DELETE FROM chitiet_gh WHERE GH_MA = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cart_id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

/**
 * Kiểm tra sản phẩm có tồn tại trong giỏ hàng không
 * @param int $cart_id ID của giỏ hàng
 * @param int $product_id ID của sản phẩm
 * @return bool Tồn tại hay không
 */
function isProductInCart($conn, $cart_id, $product_id) {
    $sql = "SELECT COUNT(*) as count FROM chitiet_gh WHERE GH_MA = ? AND SP_MA = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $cart_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['count'] > 0;
}

/**
 * Áp dụng mã giảm giá cho giỏ hàng
 * @param string $code Mã giảm giá
 * @return array Thông tin mã giảm giá
 */
function applyPromoCode($conn, $code) {
    $current_date = date('Y-m-d');
    
    $sql = "SELECT * FROM khuyen_mai 
            WHERE Code = ? 
            AND KM_TGBD <= ? 
            AND KM_TGKT >= ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $code, $current_date, $current_date);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $promo = $result->fetch_assoc();
        $stmt->close();
        return array(
            'valid' => true,
            'id' => $promo['KM_MA'],
            'code' => $promo['Code'],
            'value' => $promo['KM_GIATRI']
        );
    }
    
    $stmt->close();
    return array('valid' => false);
}

/**
 * Lấy lịch sử mua hàng của người dùng
 * @param int $user_id ID của khách hàng
 * @return array Mảng chứa lịch sử mua hàng
 */
function getPurchaseHistory($conn, $user_id) {
    $sql = "SELECT gh.GH_MA, 
                   GROUP_CONCAT(sp.SP_TEN SEPARATOR ', ') as products,
                   SUM(sp.SP_DONGIA * c.CTGH_KHOILUONG) as total,
                   MAX(c.DA_MUA) as purchase_date
            FROM gio_hang gh
            JOIN chitiet_gh c ON gh.GH_MA = c.GH_MA
            JOIN san_pham sp ON c.SP_MA = sp.SP_MA
            WHERE gh.KH_MA = ? AND c.DA_MUA = 1
            GROUP BY gh.GH_MA
            ORDER BY purchase_date DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $history = array();
    while ($row = $result->fetch_assoc()) {
        $history[] = array(
            'cart_id' => $row['GH_MA'],
            'products' => $row['products'],
            'total' => $row['total'],
            'purchase_date' => $row['purchase_date']
        );
    }
    
    $stmt->close();
    return $history;
}

/**
 * Lấy chi tiết đơn hàng đã mua
 * @param int $cart_id ID của giỏ hàng
 * @return array Chi tiết đơn hàng
 */
function getPurchaseDetails($conn, $cart_id) {
    $sql = "SELECT sp.SP_MA, sp.SP_TEN, sp.SP_HINHANH, sp.SP_DONGIA, 
                   c.CTGH_KHOILUONG, c.CTGH_DONVITINH, c.DA_MUA
            FROM chitiet_gh c
            JOIN san_pham sp ON c.SP_MA = sp.SP_MA
            WHERE c.GH_MA = ? AND c.DA_MUA = 1
            ORDER BY sp.SP_TEN ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $items = array();
    $total = 0;
    
    while ($row = $result->fetch_assoc()) {
        $subtotal = $row['SP_DONGIA'] * $row['CTGH_KHOILUONG'];
        $total += $subtotal;
        
        $items[] = array(
            'id' => $row['SP_MA'],
            'name' => $row['SP_TEN'],
            'image' => $row['SP_HINHANH'],
            'price' => $row['SP_DONGIA'],
            'quantity' => $row['CTGH_KHOILUONG'],
            'unit' => $row['CTGH_DONVITINH'],
            'subtotal' => $subtotal,
            'purchase_date' => $row['DA_MUA']
        );
    }
    
    $stmt->close();
    
    return array(
        'items' => $items,
        'total' => $total,
        'count' => count($items)
    );
}

/**
 * Mua lại sản phẩm từ đơn hàng cũ
 * @param int $old_cart_id ID của giỏ hàng cũ
 * @param int $new_cart_id ID của giỏ hàng mới
 * @return bool Trạng thái thành công
 */
function reorderFromPreviousCart($conn, $old_cart_id, $new_cart_id) {
    // Lấy thông tin các sản phẩm từ đơn hàng cũ
    $sql = "SELECT SP_MA, CTGH_KHOILUONG, CTGH_DONVITINH 
            FROM chitiet_gh 
            WHERE GH_MA = ? AND DA_MUA = 1";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $old_cart_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $success = true;
    
    while ($row = $result->fetch_assoc()) {
        // Kiểm tra tồn kho trước khi thêm vào giỏ hàng mới
        $stock_check = "SELECT SP_SOLUONGTON FROM san_pham WHERE SP_MA = ?";
        $stock_stmt = $conn->prepare($stock_check);
        $stock_stmt->bind_param("i", $row['SP_MA']);
        $stock_stmt->execute();
        $stock_result = $stock_stmt->get_result();
        $stock_data = $stock_result->fetch_assoc();
        
        if ($stock_data['SP_SOLUONGTON'] >= $row['CTGH_KHOILUONG']) {
            // Thêm sản phẩm vào giỏ hàng mới
            if (!addToCart($conn, $new_cart_id, $row['SP_MA'], $row['CTGH_KHOILUONG'], $row['CTGH_DONVITINH'])) {
                $success = false;
                break;
            }
        }
        $stock_stmt->close();
    }
    
    $stmt->close();
    return $success;
}

/**
 * Cập nhật trạng thái DA_MUA cho nhiều sản phẩm
 * @param mysqli $conn Kết nối database
 * @param int $cart_id ID của giỏ hàng
 * @param array $product_ids Mảng các ID sản phẩm cần cập nhật
 * @return bool Trạng thái thành công
 */
function markProductsAsPurchased($conn, $cart_id, $product_ids) {
    if (empty($product_ids)) {
        writeLog("No products to mark as purchased");
        return false;
    }

    // Bắt đầu transaction
    $conn->begin_transaction();

    try {
        // Tạo placeholders cho câu query IN
        $placeholders = str_repeat('?,', count($product_ids) - 1) . '?';
        
        // 1. Cập nhật trạng thái DA_MUA
        $update_sql = "UPDATE chitiet_gh SET DA_MUA = 1 WHERE GH_MA = ? AND SP_MA IN ($placeholders)";
        $stmt = $conn->prepare($update_sql);
        if (!$stmt) {
            throw new Exception("Error preparing update query: " . $conn->error);
        }
        
        // Tạo mảng params cho bind_param
        $params = array_merge(['i'], array_fill(0, count($product_ids), 'i'));
        $types = implode('', $params);
        $values = array_merge([$cart_id], $product_ids);
        
        // Bind các tham số
        $bind_params = array();
        $bind_params[] = $types;
        foreach ($values as $key => $value) {
            $bind_params[] = $value;
        }
        
        // Bind tham số động
        $tmp = array();
        foreach ($bind_params as $key => $value) {
            $tmp[$key] = &$bind_params[$key];
        }
        call_user_func_array(array($stmt, 'bind_param'), $tmp);
        
        if (!$stmt->execute()) {
            throw new Exception("Error updating DA_MUA status: " . $stmt->error);
        }
        $stmt->close();

        // 2. Xóa khỏi selected_cart_items
        $delete_sql = "DELETE FROM selected_cart_items WHERE GH_MA = ? AND SP_MA IN ($placeholders)";
        $delete_stmt = $conn->prepare($delete_sql);
        if (!$delete_stmt) {
            throw new Exception("Error preparing delete query: " . $conn->error);
        }

        // Sử dụng lại các tham số từ câu lệnh update
        $tmp = array();
        foreach ($bind_params as $key => $value) {
            $tmp[$key] = &$bind_params[$key];
        }
        call_user_func_array(array($delete_stmt, 'bind_param'), $tmp);

        if (!$delete_stmt->execute()) {
            throw new Exception("Error deleting from selected_cart_items: " . $delete_stmt->error);
        }
        $delete_stmt->close();

        // Commit transaction
        $conn->commit();
        
        writeLog("Successfully marked products as purchased and removed from selection", [
            'cart_id' => $cart_id,
            'product_ids' => $product_ids
        ]);
        
        return true;
    } catch (Exception $e) {
        // Rollback nếu có lỗi
        $conn->rollback();
        writeLog("Error in markProductsAsPurchased: " . $e->getMessage());
        return false;
    }
}

/**
 * Tính số tiền giảm giá dựa trên tổng tiền, loại khuyến mãi và giá trị khuyến mãi
 * @param float $total Tổng tiền hàng gốc
 * @param string $discount_type Loại khuyến mãi ('percent' hoặc 'fixed')
 * @param float $discount_value Giá trị khuyến mãi (phần trăm hoặc số tiền)
 * @return float Số tiền giảm giá
 */
function calculateDiscount($total, $discount_type, $discount_value) {
    if ($discount_type === 'percent') {
        // Đảm bảo giá trị phần trăm hợp lệ (0-100)
        $discount_value = min(100, max(0, $discount_value));
        // Tính giảm giá và làm tròn đến số nguyên
        return round($total * ($discount_value / 100));
    } elseif ($discount_type === 'fixed') {
        return min($discount_value, $total);
    }
    return 0;
}

/**
 * Tính tổng thanh toán cuối cùng
 * @param float $total Tổng tiền hàng gốc
 * @param float $discount Số tiền giảm giá
 * @param float $shipping_fee Phí vận chuyển
 * @return float Tổng thanh toán
 */
function calculateTotalPayment($total, $discount, $shipping_fee) {
    // Đảm bảo các giá trị không âm
    $total = max(0, $total);
    $discount = max(0, min($discount, $total)); // Giảm giá không vượt quá tổng tiền
    $shipping_fee = max(0, $shipping_fee);
    
    // Tính tổng thanh toán và làm tròn đến số nguyên
    return round($total - $discount + $shipping_fee);
}
?> 