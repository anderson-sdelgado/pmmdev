<?php

require('./dao/InserirBolFechadoDAO.class.php');
require('./dao/InserirDadosDAO.class.php');

$inserirBolFechadoDAO = new InserirBolFechadoDAO();
$inserirDadosDAO = new InserirDadosDAO();
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

    //$dados = '{"boletim":[{"ativPrincBoletim":270,"codEquipBoletim":370,"codMotoBoletim":11,"codTurnoBoletim":3,"dataHoraBoletim":"23/08/2017 14:56","hodometroFinalBoletim":3.0,"hodometroInicialBoletim":7.0,"idBoletim":1,"idExtBoletim":33,"implemento1Boletim":2,"implemento2Boletim":0,"implemento3Boletim":0,"osBoletim":98282,"rendimentoBoletim":0.0,"statusBoletim":1,"transbordoBoletim":0}]}_{"item":[]}|{"rendimento":[{"idBolRend":1,"idExtBolRend":33,"idRend":1,"nroOSRend":98282,"valorRend":1.0}]}';

    $dados = $info['dado'];
    $inserirDadosDAO->salvarDados($dados, "inserirbolfechadodt");
    $pos1 = strpos($dados, "_") + 1;
    $pos2 = strpos($dados, "|") + 1;
    $pos3 = strpos($dados, "#") + 1;
    $pos4 = strpos($dados, "?") + 1;
    $pos5 = strpos($dados, "@") + 1;
    $c = substr($dados, 0, ($pos1 - 1));
    $amm = substr($dados, $pos1, (($pos2 - 1) - $pos1));
    $i = substr($dados, $pos2, (($pos3 - 1) - $pos2));
    $r = substr($dados, $pos3, (($pos4 - 1) - $pos3));
    $af = substr($dados, $pos4, (($pos5 - 1) - $pos4));
    $rm = substr($dados, $pos5);

    $jsonObjBoletim = json_decode($c);
    $jsonObjAponta = json_decode($amm);
    $jsonObjImplemento = json_decode($i);
    $jsonObjRendimento = json_decode($r);
    $jsonObjApontaAplicFert = json_decode($af);
    $jsonObjRecolMang = json_decode($rm);
    $dadosBoletim = $jsonObjBoletim->boletim;
    $dadosAponta = $jsonObjAponta->aponta;
    $dadosImplemento = $jsonObjImplemento->implemento;
    $dadosRendimento = $jsonObjRendimento->rendimento;
    $dadosApontaAplicFert = $jsonObjApontaAplicFert->apontaaplicfert;
    $dadosRecolMang = $jsonObjRecolMang->recolmang;

    //$insBolFechadoMMDAO->salvarDados($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosRendimento, $dadosApontaAplicFert, $dadosRecolMang);
    //echo 'GRAVOU-BOLFECHADO';

    echo $inserirBolFechadoDAO->salvarDados($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosRendimento, $dadosApontaAplicFert, $dadosRecolMang);

endif;

