<?php

require('./dao/PressaoBocal2DAO.class.php');

$pressaoBocalDAO = new PressaoBocal2DAO();

//cria o array associativo
$dados = array("dados"=>$pressaoBocalDAO->dados());

//converte o conteúdo do array associativo para uma string JSON
$json_str = json_encode($dados);

//imprime a string JSON
echo $json_str;
