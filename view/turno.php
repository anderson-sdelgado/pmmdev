<?php

$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

require_once('../control/BaseDadosCTR.class.php');

if (isset($info)):

    $baseDadosCTR = new BaseDadosCTR();
    echo $baseDadosCTR->dadosTurno($info);
    
endif;
