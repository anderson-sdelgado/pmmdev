<?php

require('./dao/InserirApontMM2DAO.class.php');
require('./dao/InserirDadosDAO.class.php');

$inserirApontDAO = new InserirApontMM2DAO();
$inserirDadosDAO = new InserirDadosDAO();
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$retorno = '';

if (isset($info)):

    $dados = $info['dado'];
    $inserirDadosDAO->salvarDados($dados, "inserirapontdt");
    $pos1 = strpos($dados, "_") + 1;
    $pos2 = strpos($dados, "|") + 1;
    $pos3 = strpos($dados, "#") + 1;
    
    $apontmm = substr($dados, 0, ($pos1 - 1));
    $imp = substr($dados, $pos1, (($pos2 - 1) - $pos1));
    $bolpneu = substr($dados, $pos2, (($pos3 - 1) - $pos2));
    $itempneu = substr($dados, $pos3);
    
    $jsonObjAponta = json_decode($apontmm);
    $jsonObjImplemento = json_decode($imp);
    $jsonObjBolPneu = json_decode($bolpneu);
    $jsonObjItemPneu = json_decode($itempneu);
    $dadosAponta = $jsonObjAponta->aponta;
    $dadosImplemento = $jsonObjImplemento->implemento;
    $dadosBolPneu = $jsonObjBolPneu->bolpneu;
    $dadosItemPneu = $jsonObjItemPneu->itempneu;

    $inserirApontDAO->salvarDados($dadosAponta, $dadosImplemento, $dadosBolPneu, $dadosItemPneu);

    echo 'GRAVOU-APONTAMM';
    
endif;


