<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

require_once('../control/CarregCTR.class.php');

if (isset($info)):

    $carregCTR = new CarregCTR();
    echo $carregCTR->atualLeiraDescarreg($versao, $info, "inserirleiradescarreg");
    
endif;