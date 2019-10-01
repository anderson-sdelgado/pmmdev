<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);

require_once('../control/LeiraCTR.class.php');

$leiraCTR = new LeiraCTR();

echo $leiraCTR->dados($versao);
