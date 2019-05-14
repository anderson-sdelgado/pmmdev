<?php

require('./dao/Atividade2DAO.class.php');

$atividadeDAO = new Atividade2DAO();

//cria o array associativo
$dados = array("dados"=>$atividadeDAO->dados());

//converte o conteúdo do array associativo para uma string JSON
$json_str = json_encode($dados);

//imprime a string JSON
echo $json_str;
