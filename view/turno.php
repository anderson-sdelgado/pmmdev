<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);

require_once('../control/BaseDadosCTR.class.php');

$baseDadosCTR = new BaseDadosCTR();

echo $baseDadosCTR->dadosTurno($versao);
