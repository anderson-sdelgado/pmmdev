<?php

require('./dao/EquipDAO.class.php');

$equipDAO = new EquipDAO();

//cria o array associativo
$dados = array("dados"=>$equipDAO->dados());

//converte o conteúdo do array associativo para uma string JSON
$json_str = json_encode($dados);

//imprime a string JSON
echo $json_str;