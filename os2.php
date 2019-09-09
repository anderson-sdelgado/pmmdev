<?php

require('./control_v1/OSCTR.class.php');

$osCTR = new OSCTR();
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

    echo $retorno = $osCTR->dados($info);

endif;
