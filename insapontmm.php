<?php

require('./dao/InsApontMMDAO.class.php');
require('./dao/InserirDadosDAO.class.php');

$insApontMMDAO = new InsApontMMDAO();
$inserirDadosDAO = new InserirDadosDAO();
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$retorno = '';

if (isset($info)):

    $dados = $info['dado'];
    $inserirDadosDAO->salvarDados($dados, "insapontmm");
    $pos1 = strpos($dados, "|") + 1;
    $pos2 = strpos($dados, "?") + 1;
    $amm = substr($dados, 0, ($pos1 - 1));
    $i = substr($dados, $pos1, (($pos2 - 1) - $pos1));
    $af = substr($dados, $pos2);
    
    
    $jsonObjAponta = json_decode($amm);
    $jsonObjImplemento = json_decode($i);
    $jsonObjApontaAplicFert = json_decode($af);
    $dadosAponta = $jsonObjAponta->aponta;
    $dadosImplemento = $jsonObjImplemento->implemento;
    $dadosApontaAplicFert = $jsonObjApontaAplicFert->apontaaplicfert;

    $insApontMMDAO->salvarDados($dadosAponta, $dadosImplemento, $dadosApontaAplicFert);
    
    echo 'GRAVOU-APONTAMM';
    
endif;


