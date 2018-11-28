<?php

require('./dao/VerOSDAO.class.php');

$verOSDAO = new VerOSDAO();
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

    $retorno = $verOSDAO->dados($info['dado']);

endif;

echo $retorno;

