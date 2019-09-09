<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);

require_once('../control/RFuncaoAtivParCTR.class.php');

$rFuncaoAtivParCTR = new RFuncaoAtivParCTR();

echo $rFuncaoAtivParCTR->dados($versao);
