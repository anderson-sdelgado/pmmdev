<?php

require('./dao/VerifPneuDAO.class.php');

$verifPneuDAO = new VerifPneuDAO();
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

    $retorno = $verifPneuDAO->dados($info['dado']);

endif;

echo $retorno;
