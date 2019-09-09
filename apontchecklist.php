<?php

require('./control_v1/InserirCheckListCTR.class.php');

$inserirCheckListCTR = new InserirCheckListCTR();
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

    echo $inserirCheckListCTR->salvarDados($info, 'apontchecklist');
    
endif;
