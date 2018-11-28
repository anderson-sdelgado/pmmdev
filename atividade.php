<?php

require('./dao/AtividadeDAO.class.php');

$atividadeDAO = new AtividadeDAO();

//cria o array associativo
$dados = array("dados"=>$atividadeDAO->dados());

//converte o conteúdo do array associativo para uma string JSON
$json_str = json_encode($dados);

//imprime a string JSON
echo $json_str;
