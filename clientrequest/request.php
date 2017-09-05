<?php
//Get JSON data from provided file
$payload = file_get_contents('payload.json');

//Setup URL for PHP app
$url = 'http://localhost/vectorapp/index.php';

$ch = curl_init($url);

//Setup curl options
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

$result = curl_exec($ch);

curl_close($ch);


