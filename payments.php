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
    $paramsBeforeEncode = $params;
    $params=json_encode($params);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
    $output=curl_exec($ch);
    $decoded = json_decode($output);
    $message = (string) $decoded->message;
    $itemAtual =  $paramsBeforeEncode['coin_address']  . " " .  $paramsBeforeEncode['actual_amount'] . " " . $message;
    echo $itemAtual . "<br>";
    $logsUse=fopen("logs.txt","a");
    fwrite($logsUse,$itemAtual . "\n");

}
$tonce = round(microtime(true) * 1000);
$url = "https://api.coinex.com/v1/balance/coin/withdraw";
$access_id = "YOUR_ACESSID"; //access_id
$secret_key = "YOURSECRETKEY"; //secret_key

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
