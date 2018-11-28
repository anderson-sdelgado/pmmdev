<?php

require('./dao/MotoristaDAO.class.php');

$motoristaDAO = new MotoristaDAO();

//cria o array associativo
$dados = array("dados"=>$motoristaDAO->dados());

//converte o conteúdo do array associativo para uma string JSON
$json_str = json_encode($dados);

//imprime a string JSON
echo $json_str;
