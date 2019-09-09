<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);

require_once('../control/EquipSegCTR.class.php');

$equipSegCTR = new EquipSegCTR();

echo $equipSegCTR->dados($versao);