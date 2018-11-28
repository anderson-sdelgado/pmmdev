<?php

require('./dao/DataHoraDAO.class.php');

$dataHoraDAO = new DataHoraDAO();

//cria o array associativo
$dados = array("dados"=>$dataHoraDAO->dados());

//converte o conteúdo do array associativo para uma string JSON
$json_str = json_encode($dados);

//imprime a string JSON
echo $json_str;
