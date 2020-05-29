<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

require_once('../control/PreCECCTR.class.php');

if (isset($info)):
    
    $preCECCTR = new PreCECCTR();
    echo $preCECCTR->salvarDados($versao, $info, 'inserirprecec');
    
endif;
