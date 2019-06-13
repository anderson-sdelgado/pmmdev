<?php

require('./control/InserirCheckListCTR.class.php');

$inserirCheckListCTR = new InserirCheckListCTR();
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

    echo $inserirCheckListCTR->salvarDados($info, 'apontchecklistdt');
    
endif;
