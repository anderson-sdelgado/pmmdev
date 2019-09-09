<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

require_once('../control/PerdaCTR.class.php');

$perdaCTR = new PerdaCTR();

if (isset($info)):

    echo $retorno = $perdaCTR->dados($versao, $info);

endif;