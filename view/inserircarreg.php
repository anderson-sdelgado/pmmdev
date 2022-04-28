<?php

$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

require_once('../control/CarregCTR.class.php');

if (isset($info)):

    $carregCTR = new CarregCTR();
    echo $carregCTR->salvarDados($info);
    
endif;