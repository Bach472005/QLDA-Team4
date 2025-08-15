<?php
class PaymentController
{
    public $paymentModel;
    public function __construct()
    {
        $this->paymentModel = new MoMoPayment();
    }
    public function momo_payment()
    {
        $amount = $_GET['amount'] ?? 10000;

        $response = $this->paymentModel->createPayment($amount);

        if (isset($response['payUrl'])) {
            header('Location: ' . $response['payUrl']);
            exit;
        } else {
            echo "Không tạo được link thanh toán MOMO.";
        }
    }

    public function momo_return()
    {
        require_once __DIR__ . "/../views/payment/result.php";
    }

    public function momo_notify()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        file_put_contents(__DIR__ . '/../../../logs/momo_notify.log', print_r($data, true), FILE_APPEND);
        http_response_code(200);
    }
}
