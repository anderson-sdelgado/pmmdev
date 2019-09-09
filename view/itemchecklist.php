<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);

require_once('../control/CheckListCTR.class.php');

$checkListCTR = new CheckListCTR();

echo $checkListCTR->dadosItem($versao);
