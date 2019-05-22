<?php

require('./dao/InserirApontFert2DAO.class.php');
require('./dao/InserirDadosDAO.class.php');

$inserirApontDAO = new InserirApontFert2DAO();
$inserirDadosDAO = new InserirDadosDAO();
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$retorno = '';

if (isset($info)):

    $dados = $info['dado'];
    $inserirDadosDAO->salvarDados($dados, "inserirapontfert2");
    $pos1 = strpos($dados, "_") + 1;
    $pos2 = strpos($dados, "|") + 1;
    
    $apontfert = substr($dados, 0, ($pos1 - 1));
    $bolpneu = substr($dados, $pos1, (($pos2 - 1) - $pos1));
    $itempneu = substr($dados, $pos2);
    
    $jsonObjAponta = json_decode($apontfert);
    $jsonObjBolPneu = json_decode($bolpneu);
    $jsonObjItemPneu = json_decode($itempneu);
    $dadosAponta = $jsonObjAponta->aponta;
    $dadosBolPneu = $jsonObjBolPneu->bolpneu;
    $dadosItemPneu = $jsonObjItemPneu->itempneu;

    $inserirApontDAO->salvarDados($dadosAponta, $dadosBolPneu, $dadosItemPneu);

    echo 'GRAVOU-APONTAFERT';
    
endif;


