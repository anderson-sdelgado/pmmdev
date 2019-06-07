<?php

require('./model/InserirDadosMM.class.php');

$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

    $inserirDadosMM = new InserirDadosMM();
    echo $inserirDadosMM->salvarDadosBolFechadoMM($info, "inserirbolfechadomm2");
    
endif;

