<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

require_once('../control/OSCTR.class.php');

if (isset($info)):
    
    $osCTR = new OSCTR();
    echo $osCTR->dados($versao, $info);

endif;
