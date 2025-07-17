<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
date_default_timezone_set('Asia/Ho_Chi_Minh');

class payment_vnpay 
{
    private $log_file;
    private $vnp_HashSecret = "MMZXWISZNUUUNKGOZQPCPASLLTHYGMTB"; // Chuỗi bí mật
    private $vnp_TmnCode = "262XSFHX"; // Mã website tại VNPAY

    public function __construct() {
        $this->log_file = dirname(__DIR__) . '/logs/vnpay_payment.log';
        
        // Tạo thư mục logs nếu chưa tồn tại
        if (!is_dir(dirname($this->log_file))) {
            mkdir(dirname($this->log_file), 0777, true);
        }
        
        // Tạo file log nếu chưa tồn tại
        if (!file_exists($this->log_file)) {
            file_put_contents($this->log_file, "\xEF\xBB\xBF"); // Add UTF-8 BOM
            chmod($this->log_file, 0666);
        }
    }

    private function write_log($message) {
        $log_entry = sprintf("[%s] %s\n", 
            date('Y-m-d H:i:s'),
            mb_convert_encoding($message, 'UTF-8', 'UTF-8')
        );
        file_put_contents($this->log_file, $log_entry, FILE_APPEND | LOCK_EX);
    }

    // Hàm tạo chuỗi hash data chuẩn
    private function createHashData($inputData) {
        ksort($inputData);
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        return $hashdata;
    }

    // Hàm tạo chữ ký
    private function createSecureHash($hashData) {
        return hash_hmac('sha512', $hashData, $this->vnp_HashSecret);
    }

    function payment_vnpay($order_id, $order_price, $order_info = null) {
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://localhost/web/phanbon_Website/thanhtoan/success.php";
        
        // Kiểm tra số tiền
        if ($order_price <= 0) {
            $this->write_log("Lỗi: Số tiền không hợp lệ cho đơn hàng $order_id");
            die("Lỗi: Số tiền không hợp lệ");
        }

        $vnp_TxnRef = $order_id; // Mã đơn hàng
        $vnp_OrderInfo = $order_info ?? 'Thanh toan don hang #' . $order_id;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $order_price * 100;
        $vnp_Locale = 'vn';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'] ?? '::1';
        $vnp_ExpireDate = date('YmdHis', strtotime('+15 minutes'));
        $vnp_CreateDate = date('YmdHis');

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $this->vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => $vnp_CreateDate,
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => $vnp_ExpireDate
        );

        // Loại bỏ các tham số rỗng và null
        $inputData = array_filter($inputData, function ($value) {
            return $value !== "" && $value !== null;
        });

        // Tạo chuỗi hash data và chữ ký
        $hashData = $this->createHashData($inputData);
        $vnpSecureHash = $this->createSecureHash($hashData);

        // Tạo URL thanh toán
        $queryParams = [];
        foreach ($inputData as $key => $value) {
            $queryParams[] = urlencode($key) . "=" . urlencode($value);
        }
        $queryString = implode('&', $queryParams);
        $vnp_Url = $vnp_Url . "?" . $queryString . "&vnp_SecureHash=" . $vnpSecureHash;

        // Ghi log request và URL
        $this->write_log("Request to VNPAY for order $order_id: " . json_encode($inputData));
        $this->write_log("Hash Data: " . $hashData);
        $this->write_log("Secure Hash: " . $vnpSecureHash);
        $this->write_log("Final URL: " . $vnp_Url);

        // Lưu thông tin đơn hàng vào session
        $_SESSION['vnpay_order_id'] = $vnp_TxnRef;
        $_SESSION['vnpay_amount'] = $vnp_Amount;
        $_SESSION['vnpay_createdate'] = $vnp_CreateDate;
        $_SESSION['vnpay_expire'] = $vnp_ExpireDate;

        header('Location: ' . $vnp_Url);
        die();
    }

    // Hàm kiểm tra chữ ký từ VNPAY
    public function verifyResponse($vnpayData) {
        if (!isset($vnpayData['vnp_SecureHash'])) {
            $this->write_log("Error: No secure hash in response data");
            return false;
        }

        $vnp_SecureHash = $vnpayData['vnp_SecureHash'];
        unset($vnpayData['vnp_SecureHash']);
        unset($vnpayData['vnp_SecureHashType']);
        
        ksort($vnpayData);
        $i = 0;
        $hashData = "";
        foreach ($vnpayData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        
        $secureHash = hash_hmac('sha512', $hashData, $this->vnp_HashSecret);
        
        $this->write_log("Verifying VNPAY Response - Hash Data: " . $hashData);
        $this->write_log("Generated Hash: " . $secureHash);
        $this->write_log("Received Hash: " . $vnp_SecureHash);
        
        return $secureHash === $vnp_SecureHash;
    }
}
?>