<?php

require('./dao/AtualAtivParadaDAO.class.php');

$atualAtivParadaDAO = new AtualAtivParadaDAO();
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

    $retorno = $atualAtivParadaDAO->dados($info['dado']);

endif;

echo $retorno;