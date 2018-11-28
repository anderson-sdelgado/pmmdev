<?php

require('./dao/AtualClasseParadaDAO.class.php');

$atualClasseParadaDAO = new AtualClasseParadaDAO();
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

    $retorno = $atualClasseParadaDAO->dados($info['dado']);

endif;

echo $retorno;