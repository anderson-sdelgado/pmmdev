<?php

require('./dao/PneuDAO.class.php');

$pneuDAO = new PneuDAO();

//cria o array associativo
$dados = array("dados"=>$pneuDAO->dados());

//converte o conteúdo do array associativo para uma string JSON
$json_str = json_encode($dados);

//imprime a string JSON
echo $json_str;
