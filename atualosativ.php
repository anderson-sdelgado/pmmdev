<?php

require('./dao/AtualOSAtivDAO.class.php');

$atualOSAtivDAO = new AtualOSAtivDAO();
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

    $retorno = $atualOSAtivDAO->dados($info['dado']);

endif;

echo $retorno;