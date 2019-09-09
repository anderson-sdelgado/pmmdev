<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

require_once('../control/AtividadeCTR.class.php');

if (isset($info)):

    $atividadeCTR = new AtividadeCTR();
    echo $atividadeCTR->pesq($versao, $info);

endif;