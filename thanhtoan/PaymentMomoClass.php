<?php
class payment_momo
{
    public function payment_momo($order_id, $order_price, $order_info)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = $order_info;
        $amount = (int)$order_price; // Đảm bảo amount là số nguyên
        if ($amount <= 0) {
            die("Lỗi: Số tiền không hợp lệ");
        }
        // Tạo orderId duy nhất bằng cách kết hợp $order_id với thời gian
        $orderId = $order_id . "_" . time(); // Ví dụ: 124_1744090098842
        $redirectUrl = "http://localhost/shopquanao_nl/Eshopper/thanhtoan/success.php";
        $ipnUrl = "http://localhost/shopquanao_nl/Eshopper/thanhtoan/success.php";
        $extraData = "";
        
        $requestId = time() . "";
        $requestType = "payWithATM";
        // Sắp xếp các tham số theo thứ tự bảng chữ cái
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);
        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );

        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);

        // Ghi log để debug
        file_put_contents('momo_log.txt', "Response from MoMo: " . print_r($jsonResult, true) . "\n", FILE_APPEND);

        // Kiểm tra nếu không có payUrl hoặc yêu cầu thất bại
        if (!isset($jsonResult['payUrl'])) {
            die("Lỗi MoMo: " . json_encode($jsonResult));
        }

        header('Location: ' . $jsonResult['payUrl']);
    }

    public function execPostRequest($url, $data)
    {
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
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            file_put_contents('momo_log.txt', "cURL Error: " . curl_error($ch) . "\n", FILE_APPEND);
        }
        curl_close($ch);
        return $result;
    }
}
?>