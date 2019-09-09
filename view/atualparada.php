<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);

require_once('../control/ParadaCTR.class.php');

$paradaCTR = new ParadaCTR();

echo $paradaCTR->atual($versao);
