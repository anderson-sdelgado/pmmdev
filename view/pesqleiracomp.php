<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

require('../control/CarregCTR.class.php');

$carregCTR = new CarregCTR();

if (isset($info)):
    
    //$dados = '{"dados":[{"equip":663,"os":994349}]}';
    echo $carregCTR->pesqLeiraComp($versao, $info);
//    echo 'teste';
    
endif;
