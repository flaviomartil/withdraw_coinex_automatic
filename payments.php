<?php

function get_sign($params, $secret_key){
    ksort($params);
    $pre_sign_ls = array();
    foreach ($params as $key => $val){
        array_push($pre_sign_ls, "$key=$val");
    }
    array_push($pre_sign_ls, "secret_key=$secret_key");
    $pre_sign_str =  join("&", $pre_sign_ls);
    //echo "$pre_sign_str\n";
    return strtoupper(md5($pre_sign_str));
}

function send_request($url, $params, $sign){
    $headers = [
        'authorization:'.$sign,
        'Content-type: application/json'
    ];

    $params=json_encode($params);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
    $output=curl_exec($ch);
    echo  $output;
}
$tonce = round(microtime(true) * 1000);
$url = "https://api.coinex.com/v1/balance/coin/withdraw";
$access_id = "9BCF2B39AD5C40B2A2F8DE4E85A0A4F4"; //access_id
$secret_key = "B50395945B5867226E8AE526A70BA2E42FE710808149D5A7"; //secret_key

$file = "test.txt";
$contents = file_get_contents($file);
$lines = explode("\n", $contents); // this is your array of words

foreach($lines as $word) {

    $thisLine = explode(":",$word,2);
    $emailCoinEx = $thisLine[0];
    $amount = $thisLine[1];
    $params = array(
        "access_id" => $access_id,
        "actual_amount" => "$amount",
        "coin_type" => "ERG",
        "coin_address" => "$emailCoinEx",
        "tonce" => $tonce,
        "transfer_method" => "local",
    );
    $sign = get_sign($params, $secret_key);
    send_request($url, $params, $sign);
}
?>
