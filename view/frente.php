<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);

require_once('../control/FrenteCTR.class.php');

$frenteCTR = new FrenteCTR();

echo $frenteCTR->dados($versao);
