<?php

require_once('./control/InserirDadosFertCTR.class.php');

$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

    $inserirDadosFertCTR = new InserirDadosFertCTR();
    echo $inserirDadosFertCTR->salvarDadosBolAbertoFert($info, "inserirbolabertofert2");

endif;