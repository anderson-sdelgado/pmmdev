<?php

require('./dao/ChecklistDAO.class.php');

$checklistDAO = new ChecklistDAO();

//cria o array associativo
$dados = array("dados"=>$checklistDAO->dados());

//converte o conteúdo do array associativo para uma string JSON
$json_str = json_encode($dados);

//imprime a string JSON
echo $json_str;
