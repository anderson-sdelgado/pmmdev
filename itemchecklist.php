<?php

require('./dao/ItemChecklistDAO.class.php');

$itemChecklistDAO = new ItemChecklistDAO();

//cria o array associativo
$dados = array("dados"=>$itemChecklistDAO->dados());

//converte o conteúdo do array associativo para uma string JSON
$json_str = json_encode($dados);

//imprime a string JSON
echo $json_str;
