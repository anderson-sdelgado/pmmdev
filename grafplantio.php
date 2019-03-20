<?php

require('./dao/GrafPlantioDAO.class.php');

$grafPlantioDAO = new GrafPlantioDAO();

$retorno = $grafPlantioDAO->dados();

echo $retorno;