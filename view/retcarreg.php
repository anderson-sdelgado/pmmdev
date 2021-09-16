<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

require('../control/CarregCTR.class.php');

$carregCTR = new CarregCTR();

if (isset($info)):

    echo $carregCTR->retCarreg($versao, $info);
    
endif;
