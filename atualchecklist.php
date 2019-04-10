<?php

require('./dao/VerifEquipDAO.class.php');

$verifEquipDAO = new VerifEquipDAO();
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

    $retorno = $verifEquipDAO->dados($info['dado']);
    echo $retorno;

endif;


