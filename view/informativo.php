<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

require_once('../control/InformativoCTR.class.php');

$informativoCTR = new InformativoCTR();

if (isset($info)):

    echo $retorno = $informativoCTR->dados($versao, $info);

endif;
