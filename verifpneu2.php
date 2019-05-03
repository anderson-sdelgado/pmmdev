<?php

require('./dao/VerifPneu2DAO.class.php');

$verifPneuDAO = new VerifPneu2DAO();
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

    $retorno = $verifPneuDAO->dados($info['dado']);

endif;

echo $retorno;
