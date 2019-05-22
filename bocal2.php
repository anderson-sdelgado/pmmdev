<?php

require('./dao/Bocal2DAO.class.php');

$bocalDAO = new Bocal2DAO();

//cria o array associativo
$dados = array("dados"=>$bocalDAO->dados());

//converte o conteúdo do array associativo para uma string JSON
$json_str = json_encode($dados);

//imprime a string JSON
echo $json_str;