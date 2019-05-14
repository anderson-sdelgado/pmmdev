<?php

require('./dao/Motorista2DAO.class.php');

$motoristaDAO = new Motorista2DAO();

//cria o array associativo
$dados = array("dados"=>$motoristaDAO->dados());

//converte o conteúdo do array associativo para uma string JSON
$json_str = json_encode($dados);

//imprime a string JSON
echo $json_str;
