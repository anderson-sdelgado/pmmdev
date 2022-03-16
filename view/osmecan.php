<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

require_once('../control/BaseDadosCTR.class.php');

$baseDadosCTR = new BaseDadosCTR();

if (isset($info)):

    echo $baseDadosCTR->dadosOSMecan($versao, $info);

endif;