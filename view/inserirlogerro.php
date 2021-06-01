<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

require_once('../control/LogErroCTR.php ');

if (isset($info)):

    $logErroCTR = new LogErroCTR();
    echo $logErroCTR->salvarLog($versao, $info);
    
endif;
