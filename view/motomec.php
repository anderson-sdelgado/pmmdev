<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);

require_once('../control/MotoMecCTR.class.php');

$motoMecCTR = new MotoMecCTR();

echo $motoMecCTR->dados($versao);
