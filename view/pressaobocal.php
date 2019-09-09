<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);

require_once('../control/PressaoBocalCTR.class.php');

$pressaoBocalCTR = new PressaoBocalCTR();

echo $pressaoBocalCTR->dados($versao);
