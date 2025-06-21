<?php
require_once 'cart_functions.php';

function selectCartItem($conn, $cart_id, $product_id) {
    try {
        writeLog("Selecting product_id: $product_id for cart_id: $cart_id");
        
        // Kiểm tra đã tồn tại chưa
        $sql_check = "SELECT 1 FROM selected_cart_items WHERE GH_MA = ? AND SP_MA = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ii", $cart_id, $product_id);
        $stmt_check->execute();
        $stmt_check->store_result();
        if ($stmt_check->num_rows > 0) {
            $stmt_check->close();
            writeLog("Product_id: $product_id already selected in cart_id: $cart_id");
            return ['success' => true]; // Đã tồn tại, coi như thành công
        }
        $stmt_check->close();

        // Nếu chưa có thì thêm mới
        $sql = "INSERT INTO selected_cart_items (GH_MA, SP_MA) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $cart_id, $product_id);
        $result = $stmt->execute();
        $stmt->close();
        writeLog("Successfully selected product_id: $product_id for cart_id: $cart_id");
        return ['success' => true];
    } catch (Exception $e) {
        writeLog("Error selecting product_id: $product_id for cart_id: $cart_id - " . $e->getMessage());
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

function unselectCartItem($conn, $cart_id, $product_id) {
    try {
        writeLog("Unselecting product_id: $product_id from cart_id: $cart_id");
        $sql = "DELETE FROM selected_cart_items WHERE GH_MA = ? AND SP_MA = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $cart_id, $product_id);
        $result = $stmt->execute();
        $stmt->close();
        writeLog("Successfully unselected product_id: $product_id from cart_id: $cart_id");
        return ['success' => true];
    } catch (Exception $e) {
        writeLog("Error unselecting product_id: $product_id from cart_id: $cart_id - " . $e->getMessage());
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

function getSelectedItems($conn, $cart_id) {
    writeLog("Getting selected items for cart_id: $cart_id");
    $sql = "SELECT SP_MA FROM selected_cart_items WHERE GH_MA = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $selected_items = [];
    while ($row = $result->fetch_assoc()) {
        $selected_items[] = $row['SP_MA'];
    }
    $stmt->close();
    writeLog("Found " . count($selected_items) . " selected items for cart_id: $cart_id");
    return $selected_items;
}

function clearSelectedItems($conn, $cart_id) {
    writeLog("Clearing all selected items for cart_id: $cart_id");
    $sql = "DELETE FROM selected_cart_items WHERE GH_MA = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cart_id);
    $result = $stmt->execute();
    $stmt->close();
    writeLog("Successfully cleared selected items for cart_id: $cart_id");
    return $result;
}

function moveSelectedItemsToNewCart($conn, $old_cart_id, $new_cart_id) {
    try {
        $conn->begin_transaction();

        // Get selected items
        $selected_items = getSelectedItems($conn, $old_cart_id);
        
        foreach ($selected_items as $product_id) {
            // Get item details from old cart
            $sql = "SELECT CTGH_KHOILUONG, CTGH_DONVITINH FROM chitiet_gh 
                   WHERE GH_MA = ? AND SP_MA = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $old_cart_id, $product_id);
            $stmt->execute();
            $item = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if ($item) {
                // Move item to new cart
                $sql = "INSERT INTO chitiet_gh (GH_MA, SP_MA, CTGH_KHOILUONG, CTGH_DONVITINH) 
                       VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iids", $new_cart_id, $product_id, $item['CTGH_KHOILUONG'], $item['CTGH_DONVITINH']);
                $stmt->execute();
                $stmt->close();

                // Remove item from old cart
                $sql = "DELETE FROM chitiet_gh WHERE GH_MA = ? AND SP_MA = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $old_cart_id, $product_id);
                $stmt->execute();
                $stmt->close();
            }
        }

        // Clear selected items
        clearSelectedItems($conn, $old_cart_id);

        $conn->commit();
        return ['success' => true];
    } catch (Exception $e) {
        $conn->rollback();
        return ['success' => false, 'message' => $e->getMessage()];
    }
}
?> 