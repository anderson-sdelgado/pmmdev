<?php

require('./dao/AtualizaAplicDAO.class.php');

$atualizaAplicDAO = new AtualizaAplicDAO();
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):
    
    //$dados = '{"dados":[{"idEquipAtualizacao":663,"versaoAtual":"1.1"}]}';
    
    $jsonObj = json_decode($info['dado']);
    //$jsonObj = json_decode($dados); //Teste
    $dados = $jsonObj->dados;
    $retorno = $atualizaAplicDAO->pesqInfo($dados);
    
endif;

echo $retorno;