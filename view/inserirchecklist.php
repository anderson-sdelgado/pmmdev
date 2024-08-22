<?php

require_once('../control/CheckListCTR.class.php');
require_once('../control/AtualAplicCTR.class.php');

$headers = getallheaders();;
header('Content-type: application/json');
$body = file_get_contents('php://input');

if (!array_key_exists('Authorization', $headers)) {
    echo json_encode(["error" => "Authorization header is missing"]);
    exit;
}

if(array_key_exists('Authorization', $headers)){
    $token = $headers['Authorization'];
}

if(array_key_exists('authorization', $headers)){
    $token = $headers['authorization'];
}

$atualAplicCTR = new AtualAplicCTR();
if (!$atualAplicCTR->verToken($token)){
    echo json_encode(["error" => "Invalid token"]);
    exit;
}

if (!isset($body)){
    echo json_encode(["error" => "Empty body"]);
    exit;
}

$checkListCTR = new CheckListCTR();
echo $checkListCTR->salvarDados($body);

