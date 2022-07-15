<?php

$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

require_once('../control/LogProcessoCTR.php ');

if (isset($info)){

    $logProcessoCTR = new LogProcessoCTR();
    $logProcessoCTR->salvarLog($info);
    
    echo "LOGPROCESSO";
    
}
