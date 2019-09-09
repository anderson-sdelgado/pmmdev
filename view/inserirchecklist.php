<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

require_once('../control/CheckListCTR.class.php');

if (isset($info)):
    
    $checkListCTR = new CheckListCTR();
    echo $checkListCTR->salvarDados($versao, $info, 'inserirchecklist');
    
endif;

