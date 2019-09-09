<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);

require_once('../control/FuncionarioCTR.class.php');

$funcionarioCTR = new FuncionarioCTR();

echo $funcionarioCTR->dados($versao);
