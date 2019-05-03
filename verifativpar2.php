<?php

require('./dao/VerifAtivParada2DAO.class.php');

$atualAtivParadaDAO = new VerifAtivParada2DAO();
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

    $retorno = $atualAtivParadaDAO->dados($info['dado']);

endif;

echo $retorno;