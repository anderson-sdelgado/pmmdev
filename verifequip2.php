<?php

require('./dao/VerifEquip2DAO.class.php');

$verifEquipDAO = new VerifEquip2DAO();
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

    $retorno = $verifEquipDAO->dados($info['dado']);

endif;

echo $retorno;
