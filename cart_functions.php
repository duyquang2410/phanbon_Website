<?php
// File quản lý các chức năng liên quan đến giỏ hàng

/**
 * Tạo giỏ hàng mới cho người dùng
 * @param int $user_id ID của khách hàng
 * @return int ID của giỏ hàng vừa tạo
 */
function createCart($conn, $user_id) {
    $sql = "INSERT INTO gio_hang (KH_MA) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $cart_id = $conn->insert_id;
    $stmt->close();
    return $cart_id;
}

/**
 * Lấy giỏ hàng hiện tại của người dùng
 * @param int $user_id ID của khách hàng
 * @return int|null ID của giỏ hàng hoặc null nếu không tồn tại
 */
function getCurrentCart($conn, $user_id) {
    // Kiểm tra xem người dùng đã có giỏ hàng chưa
    $sql = "SELECT GH_MA FROM gio_hang WHERE KH_MA = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $cart_id = $row['GH_MA'];
    } else {
        // Nếu chưa có giỏ hàng, tạo giỏ hàng mới
        $cart_id = createCart($conn, $user_id);
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
    // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
    $check_sql = "SELECT * FROM chitiet_gh WHERE GH_MA = ? AND SP_MA = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $cart_id, $product_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        // Nếu sản phẩm đã có trong giỏ hàng, cập nhật số lượng
        $row = $check_result->fetch_assoc();
        $new_quantity = $row['CTGH_KHOILUONG'] + $quantity;
        
        $update_sql = "UPDATE chitiet_gh SET CTGH_KHOILUONG = ? WHERE GH_MA = ? AND SP_MA = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("dii", $new_quantity, $cart_id, $product_id);
        $result = $update_stmt->execute();
        $update_stmt->close();
    } else {
        // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới
        $insert_sql = "INSERT INTO chitiet_gh (GH_MA, SP_MA, CTGH_KHOILUONG, CTGH_DONVITINH) VALUES (?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("iids", $cart_id, $product_id, $quantity, $unit);
        $result = $insert_stmt->execute();
        $insert_stmt->close();
    }
    
    $check_stmt->close();
    return $result;
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
    $sql = "SELECT sp.SP_MA, sp.SP_TEN, sp.SP_HINHANH, sp.SP_DONGIA, c.CTGH_KHOILUONG, c.CTGH_DONVITINH 
            FROM chitiet_gh c
            JOIN san_pham sp ON c.SP_MA = sp.SP_MA
            WHERE c.GH_MA = ?
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
            'subtotal' => $subtotal
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
 * Lấy số lượng sản phẩm trong giỏ hàng
 * @param int $cart_id ID của giỏ hàng
 * @return int Số lượng sản phẩm trong giỏ hàng
 */
function getCartItemCount($conn, $cart_id) {
    $sql = "SELECT COUNT(*) as count FROM chitiet_gh WHERE GH_MA = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['count'];
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
?> 