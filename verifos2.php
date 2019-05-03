<?php

require('./dao/VerifOS2DAO.class.php');

$verifOSDAO = new VerifOS2DAO();
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

    $retorno = $verifOSDAO->dados($info['dado']);

endif;

echo $retorno;

