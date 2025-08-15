
<?php
return [
    'endpoint' => 'https://test-payment.momo.vn/v2/gateway/api/create',
    'partnerCode' => 'MOMOBKUN20180529',
    'accessKey' => 'klm05TvNBzhg7h7j',
    'secretKey' => 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa',
    'redirectUrl' => BASE_URL . "?act=momo_return",
    'ipnUrl' => BASE_URL . "?act=momo_notify",
];
