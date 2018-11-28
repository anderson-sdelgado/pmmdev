<?php

require('./dao/VerEquipDAO.class.php');

$verEquipDAO = new VerEquipDAO();
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

    $retorno = $verEquipDAO->dados($info['dado']);

endif;

echo $retorno;
