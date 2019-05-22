<?php

require('./dao/InserirBolFechadoFert2DAO.class.php');
require('./dao/InserirDadosDAO.class.php');

$inserirBolFechadoDAO = new InserirBolFechadoFert2DAO();
$inserirDadosDAO = new InserirDadosDAO();
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

    //$dados = '{"boletim":[{"ativPrincBoletim":270,"codEquipBoletim":370,"codMotoBoletim":11,"codTurnoBoletim":3,"dataHoraBoletim":"23/08/2017 14:56","hodometroFinalBoletim":3.0,"hodometroInicialBoletim":7.0,"idBoletim":1,"idExtBoletim":33,"implemento1Boletim":2,"implemento2Boletim":0,"implemento3Boletim":0,"osBoletim":98282,"rendimentoBoletim":0.0,"statusBoletim":1,"transbordoBoletim":0}]}_{"item":[]}|{"rendimento":[{"idBolRend":1,"idExtBolRend":33,"idRend":1,"nroOSRend":98282,"valorRend":1.0}]}';
    
    $dados = $info['dado'];
    $inserirDadosDAO->salvarDados($dados, "inserirbolfechadofert2");
    $pos1 = strpos($dados, "_") + 1;
    $pos2 = strpos($dados, "|") + 1;
    $pos3 = strpos($dados, "#") + 1;
    $pos4 = strpos($dados, "?") + 1;
    $bolfert = substr($dados, 0, ($pos1 - 1));
    $apontfert = substr($dados, $pos1, (($pos2 - 1) - $pos1));
    $recol = substr($dados, $pos2, (($pos3 - 1) - $pos2));
    $bolpneu = substr($dados, $pos3, (($pos4 - 1) - $pos3));
    $itempneu = substr($dados, $pos4);
    
    $jsonObjBoletim = json_decode($bolfert);
    $jsonObjAponta = json_decode($apontfert);
    $jsonObjRecolhimento = json_decode($recol);
    $jsonObjBolPneu = json_decode($bolpneu);
    $jsonObjItemPneu = json_decode($itempneu);
    $dadosBoletim = $jsonObjBoletim->boletim;
    $dadosAponta = $jsonObjAponta->aponta;
    $dadosRecolhimento = $jsonObjRecolhimento->recolhimento;
    $dadosBolPneu = $jsonObjBolPneu->bolpneu;
    $dadosItemPneu = $jsonObjItemPneu->itempneu;

    echo $inserirBolFechadoDAO->salvarDados($dadosBoletim, $dadosAponta, $dadosRecolhimento, $dadosBolPneu, $dadosItemPneu);

endif;

