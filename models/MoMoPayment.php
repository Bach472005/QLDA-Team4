<?php

class MoMoPayment
{
    private $endpoint;
    private $partnerCode;
    private $accessKey;
    private $secretKey;
    private $redirectUrl;
    private $ipnUrl;

    public function __construct()
    {
        $config = include(__DIR__ . '/../config/momo_config.php');
        
        $this->endpoint = $config['endpoint'];
        $this->partnerCode = $config['partnerCode'];
        $this->accessKey = $config['accessKey'];
        $this->secretKey = $config['secretKey'];
        $this->redirectUrl = $config['redirectUrl'];
        $this->ipnUrl = $config['ipnUrl'];
    }

    public function createPayment($amount, $orderInfo = '')
    {
        $orderId = uniqid("order_"); // Using uniqid() for a unique orderId
        $requestId = time() . '';
        $orderInfo = $orderInfo ?: "Thanh toán đơn hàng #" . $orderId;
        $extraData = '{}'; // Assign a valid JSON or string to extraData
        $requestType = 'captureWallet'; // Ensure this is the correct request type

        // Ensure IPN URL is provided
        if (empty($this->ipnUrl)) {
            throw new Exception("IPN URL must not be blank.");
        }

        // Chuỗi tạo chữ ký
        $rawHash = "accessKey={$this->accessKey}&amount={$amount}&extraData={$extraData}&ipnUrl={$this->ipnUrl}"
            . "&orderId={$orderId}&orderInfo={$orderInfo}&partnerCode={$this->partnerCode}"
            . "&redirectUrl={$this->redirectUrl}&requestId={$requestId}&requestType={$requestType}";

        $signature = hash_hmac('sha256', $rawHash, $this->secretKey);

        $data = [
            'partnerCode' => $this->partnerCode,
            'accessKey' => $this->accessKey,
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $this->redirectUrl,
            'ipnUrl' => $this->ipnUrl,
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature,
            'lang' => 'vi'
        ];

        // Gửi request đến MoMo
        $ch = curl_init($this->endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }

}
