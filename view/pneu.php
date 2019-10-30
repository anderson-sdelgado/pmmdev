<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

require_once('../control/PneuCTR.class.php');

if (isset($info)):
    
    $pneuCTR = new PneuCTR();
    echo $pneuCTR->dados($versao, $info);

endif;