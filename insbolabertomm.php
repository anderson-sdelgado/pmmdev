<?php

require_once('./control_v1/InserirDadosMMCTR.class.php');

$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

    $inserirDadosMMCTR = new InserirDadosMMCTR();
    echo $inserirDadosMMCTR->salvarDadosBolAbertoMM($info, "insbolabertomm");
    
endif;