<?php

require('./dao/VerifOSDAO.class.php');

$verifOSDAO = new VerifOSDAO();
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

    $retorno = $verifOSDAO->dados($info['dado']);

endif;

echo $retorno;

