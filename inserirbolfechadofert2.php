<?php

require_once('./control_v1/InserirDadosFertCTR.class.php');

$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

    $inserirDadosFertCTR = new InserirDadosFertCTR();
    echo $inserirDadosFertCTR->salvarDadosBolFechadoFert($info, "inserirbolfechadofert2");
    
endif;