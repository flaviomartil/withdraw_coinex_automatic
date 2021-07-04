<?php

$url = 'https://api.coinex.com/v1/balance/coin/withdraw';

$headers = [
    'authorization' => 'token'
];
//needs whitelist ip adress

$postFields = [
    'access_id' => 129309,
    'tonce' => (int)(time()*1000),
    'coin_type' => 'ergo',
    'coin_adress' => 'flaviomartil5@gmail.com',
    'transfer_method' => 'Inter-user',
    'actual_amount' => 0.10
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
$data = curl_exec($ch);

echo $data;


?>
