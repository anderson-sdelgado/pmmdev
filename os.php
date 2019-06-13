<?php

require('./dao/OSDAO.class.php');

$osDAO = new OSDAO();

//cria o array associativo
$dados = array("dados"=>$osDAO->dados());

//converte o conteúdo do array associativo para uma string JSON
$json_str = json_encode($dados);

//imprime a string JSON
echo $json_str;
