<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);

require_once('../control/AtividadeCTR.class.php');

$atividadeCTR = new AtividadeCTR();

echo $atividadeCTR->dados($versao);
