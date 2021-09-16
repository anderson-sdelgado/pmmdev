<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);

require_once('../control/MotoMecFertCTR.class.php');

$motoMecFertCTR = new MotoMecFertCTR();

echo $motoMecFertCTR->dados($versao);
