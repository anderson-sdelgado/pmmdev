<?php

require('./dao/GrafPlantioDAO.class.php');

$grafPlantioDAO = new GrafPlantioDAO();

$dados = array("dados"=>$grafPlantioDAO->dados());

$json_str = json_encode($dados);

echo $json_str;