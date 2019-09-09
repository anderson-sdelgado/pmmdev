<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

require_once('../control/MotoMecCTR.class.php');

if (isset($info)):

    $motoMecCTR = new MotoMecCTR();
    echo $motoMecCTR->salvarApont($versao, $info, "inserirapontmm");
    
endif;
