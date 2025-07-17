<?php
class payment_momo
{
    private $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
    private $partnerCode = 'MOMOBKUN20180529';
    private $accessKey = 'klm05TvNBzhg7h7j';
    private $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
    
    private function write_log($content, $type = 'INFO') {
        $log_file = '../logs/momo_payment.log';
        $log_dir = dirname($log_file);
        
        if (!file_exists($log_dir)) {
            mkdir($log_dir, 0777, true);
        }
        
        $log_content = sprintf(
            "[%s][%s] %s\n",
            date('Y-m-d H:i:s'),
            $type,
            is_array($content) || is_object($content) ? json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $content
        );
        file_put_contents($log_file, $log_content, FILE_APPEND);
    }

    public function payment_momo($order_id, $order_price, $order_info)
    {
        try {
            $this->write_log("=== Bắt đầu xử lý thanh toán MOMO ===");
            
            // Format số tiền
            $amount = (int)$order_price;
            if ($amount <= 0) {
                throw new Exception("Số tiền không hợp lệ");
            }

            // Format order info - chỉ giữ các ký tự alphanumeric và khoảng trắng
            $orderInfo = preg_replace('/[^a-zA-Z0-9\s]/', '', $order_info);
            
            // Tạo orderId duy nhất
            $orderId = $order_id . "_" . time();
            $requestId = time() . "";
            
            // Cấu hình redirect URL - Sử dụng URL tuyệt đối
            $host = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
            $host .= $_SERVER['HTTP_HOST'];
            
            $redirectUrl = $host . "/LVTN_PhanBon/thanhtoan/success.php";
            $ipnUrl = $host . "/LVTN_PhanBon/thanhtoan/success.php";
            
            // Chuẩn bị dữ liệu gửi đi
            $requestData = array(
                'partnerCode' => $this->partnerCode,
                'partnerName' => "Test",
                'storeId' => "MomoTestStore",
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'lang' => 'vi',
                'extraData' => "",
                'requestType' => "payWithMoMo",
                'autoCapture' => true,
                'orderGroupId' => "",
                'signature' => ""
            );
            
            // Tạo chuỗi raw hash theo đúng thứ tự của MOMO
            $rawHash = "accessKey=" . $this->accessKey .
                      "&amount=" . $amount .
                      "&extraData=" .
                      "&ipnUrl=" . $ipnUrl .
                      "&orderId=" . $orderId .
                      "&orderInfo=" . $orderInfo .
                      "&partnerCode=" . $this->partnerCode .
                      "&redirectUrl=" . $redirectUrl .
                      "&requestId=" . $requestId .
                      "&requestType=payWithMoMo";
            
            $this->write_log("Raw hash string: " . $rawHash, 'DEBUG');
            
            // Tạo chữ ký
            $signature = hash_hmac("sha256", $rawHash, $this->secretKey);
            $this->write_log("Generated signature: " . $signature, 'DEBUG');
            
            // Thêm chữ ký vào dữ liệu gửi đi
            $requestData['signature'] = $signature;

            $this->write_log("Request to MOMO:", 'INFO');
            $this->write_log($requestData, 'DEBUG');

            // Gọi API MoMo
            $result = $this->execPostRequest($this->endpoint, json_encode($requestData));
            $jsonResult = json_decode($result, true);

            $this->write_log("Response from MOMO:", 'INFO');
            $this->write_log($jsonResult, 'DEBUG');

            // Xử lý response
            if (!isset($jsonResult['payUrl'])) {
                $error_message = isset($jsonResult['message']) ? $jsonResult['message'] : 
                               (isset($jsonResult['resultCode']) ? $this->getErrorMessage($jsonResult['resultCode']) : 'Unknown error');
                $this->write_log("Error: " . $error_message, 'ERROR');
                throw new Exception($error_message);
            }

            // Lưu thông tin vào session
            $_SESSION['momo_order_id'] = $orderId;
            $_SESSION['original_order_id'] = $order_id;
            $_SESSION['momo_amount'] = $amount;
            $_SESSION['momo_payment_expire'] = date('YmdHis', strtotime('+15 minutes'));

            $this->write_log("=== Kết thúc xử lý thanh toán MOMO - Thành công ===");

            // Chuyển hướng đến trang thanh toán MoMo
            header('Location: ' . $jsonResult['payUrl']);
            die();

        } catch (Exception $e) {
            $this->write_log("Exception: " . $e->getMessage(), 'ERROR');
            throw $e;
        }
    }

    private function getErrorMessage($errorCode) {
        $errorMessages = [
            '0' => 'Giao dịch thành công',
            '1006' => 'Giao dịch đã bị hủy hoặc hết hạn',
            '1003' => 'Đơn hàng đã tồn tại trong hệ thống',
            '1001' => 'Giao dịch đã được thực hiện',
            '1005' => 'Số tiền không hợp lệ',
            '2001' => 'Số dư không đủ để thực hiện giao dịch',
            '2007' => 'Giao dịch bị nghi ngờ gian lận',
            '4001' => 'Giao dịch không thành công do lỗi hệ thống',
            '7000' => 'Giao dịch bị từ chối bởi ngân hàng',
            '11' => 'Đã hết thời gian thực hiện giao dịch',
            '12' => 'Kiểm tra chữ ký không thành công',
            '29' => 'Token không hợp lệ',
            '80' => 'Không tìm thấy giao dịch',
            '81' => 'Đơn hàng đã được thanh toán',
            '99' => 'Lỗi không xác định',
            '402' => 'Số tiền không hợp lệ',
            '403' => 'Mã đơn hàng không hợp lệ',
            '11007' => 'Lỗi xác thực thông tin thanh toán',
            '11008' => 'Lỗi định dạng dữ liệu không hợp lệ'
        ];
        
        return $errorMessages[$errorCode] ?? "Lỗi không xác định (Mã: $errorCode)";
    }

    public function execPostRequest($url, $data)
    {
        try {
            $this->write_log("Calling MOMO API:", 'DEBUG');
            $this->write_log([
                'url' => $url,
                'data' => json_decode($data, true)
            ], 'DEBUG');

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
            );
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            
            $result = curl_exec($ch);
            
            if ($error = curl_error($ch)) {
                $this->write_log("cURL Error: " . $error, 'ERROR');
                throw new Exception("Lỗi kết nối đến MoMo: " . $error);
            }

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $this->write_log("HTTP Response Code: " . $httpCode, 'DEBUG');
            
            curl_close($ch);
            return $result;
            
        } catch (Exception $e) {
            $this->write_log("Exception in execPostRequest: " . $e->getMessage(), 'ERROR');
            throw $e;
        }
    }
}
?>