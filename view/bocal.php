<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);

require_once('../control/BocalCTR.class.php');

$bocalCTR = new BocalCTR();

echo $bocalCTR->dados($versao);