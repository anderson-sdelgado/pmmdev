<?php

require('./control/PerdaCTR.class.php');

$perdaCTR = new PerdaCTR();
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

    echo $retorno = $perdaCTR->dados($info);

endif;