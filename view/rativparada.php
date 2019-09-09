<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);

require_once('../control/RAtivParadaCTR.class.php');

$rAtivParadaCTR = new RAtivParadaCTR();

echo $rAtivParadaCTR->dados($versao);