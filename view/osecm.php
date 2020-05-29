<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);

require_once('../control/OSCTR.class.php');

$osCTR = new OSCTR();

echo $osCTR->dadosECM($versao);
