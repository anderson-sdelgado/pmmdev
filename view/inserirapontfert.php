<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

require_once('../control/FertirrigacaoCTR.class.php');

if (isset($info)):

    $fertirrigacaoCTR = new FertirrigacaoCTR();
    echo $fertirrigacaoCTR->salvarApont($versao, $info, "inserirapontfert2");
    
endif;
