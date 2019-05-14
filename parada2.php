<?php

require('./dao/Parada2DAO.class.php');

$paradaDAO = new Parada2DAO();

//cria o array associativo
$dados = array("dados"=>$paradaDAO->dados());

//converte o conteúdo do array associativo para uma string JSON
$json_str = json_encode($dados);

//imprime a string JSON
echo $json_str;
