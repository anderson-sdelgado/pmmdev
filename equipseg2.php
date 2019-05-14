<?php

require('./dao/EquipSeg2DAO.class.php');

$equipSegDAO = new EquipSeg2DAO();

//cria o array associativo
$dados = array("dados"=>$equipSegDAO->dados());

//converte o conteúdo do array associativo para uma string JSON
$json_str = json_encode($dados);

//imprime a string JSON
echo $json_str;