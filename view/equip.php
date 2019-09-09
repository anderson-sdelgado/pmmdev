<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

require_once('../control/EquipCTR.class.php');

if (isset($info)):

    $equipCTR = new EquipCTR();
    echo $retorno = $equipCTR->dados($versao, $info);

endif;
