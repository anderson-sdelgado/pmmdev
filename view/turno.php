<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);

require_once('../control/TurnoCTR.class.php');

$turnoCTR = new TurnoCTR();

echo $turnoCTR->dados($versao);
