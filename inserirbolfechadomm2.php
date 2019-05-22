<?php

require('./dao/InserirBolFechadoMM2DAO.class.php');
require('./dao/InserirDadosDAO.class.php');

$inserirBolFechadoDAO = new InserirBolFechadoMM2DAO();
$inserirDadosDAO = new InserirDadosDAO();
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

    //$dados = '{"boletim":[{"ativPrincBoletim":270,"codEquipBoletim":370,"codMotoBoletim":11,"codTurnoBoletim":3,"dataHoraBoletim":"23/08/2017 14:56","hodometroFinalBoletim":3.0,"hodometroInicialBoletim":7.0,"idBoletim":1,"idExtBoletim":33,"implemento1Boletim":2,"implemento2Boletim":0,"implemento3Boletim":0,"osBoletim":98282,"rendimentoBoletim":0.0,"statusBoletim":1,"transbordoBoletim":0}]}_{"item":[]}|{"rendimento":[{"idBolRend":1,"idExtBolRend":33,"idRend":1,"nroOSRend":98282,"valorRend":1.0}]}';
    
    $dados = $info['dado'];
    $inserirDadosDAO->salvarDados($dados, "inserirbolfechadomm2");
    $pos1 = strpos($dados, "_") + 1;
    $pos2 = strpos($dados, "|") + 1;
    $pos3 = strpos($dados, "#") + 1;
    $pos4 = strpos($dados, "?") + 1;
    $pos5 = strpos($dados, "@") + 1;
    $bolmm = substr($dados, 0, ($pos1 - 1));
    $apontmm = substr($dados, $pos1, (($pos2 - 1) - $pos1));
    $impl = substr($dados, $pos2, (($pos3 - 1) - $pos2));
    $rend = substr($dados, $pos3, (($pos4 - 1) - $pos3));
    $bolpneu = substr($dados, $pos4, (($pos5 - 1) - $pos4));
    $itempneu = substr($dados, $pos5);
    
    $jsonObjBoletim = json_decode($bolmm);
    $jsonObjAponta = json_decode($apontmm);
    $jsonObjImplemento = json_decode($impl);
    $jsonObjRendimento = json_decode($rend);
    $jsonObjBolPneu = json_decode($bolpneu);
    $jsonObjItemPneu = json_decode($itempneu);
    $dadosBoletim = $jsonObjBoletim->boletim;
    $dadosAponta = $jsonObjAponta->aponta;
    $dadosImplemento = $jsonObjImplemento->implemento;
    $dadosRendimento = $jsonObjRendimento->rendimento;
    $dadosBolPneu = $jsonObjBolPneu->bolpneu;
    $dadosItemPneu = $jsonObjItemPneu->itempneu;

    echo $inserirBolFechadoDAO->salvarDados($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosRendimento, $dadosBolPneu, $dadosItemPneu);

endif;

