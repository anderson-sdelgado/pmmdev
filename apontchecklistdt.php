<?php

require('./dao/ApontarCheckListDAO.class.php');
require('./dao/InserirDadosDAO.class.php');

$apontarCheckListDAO = new ApontarCheckListDAO();
$inserirDadosDAO = new InserirDadosDAO();
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

    //$dados = '{"cabecalho":[{"dtCabecCheckList":"04/04/2017 13:17","equipCabecCheckList":11010,"funcCabecCheckList":1,"idCabecCheckList":1,"turnoCabecCheckList":2}]}_{"item":[{"idCabecItemCheckList":1,"idItItemCheckList":33748,"idItemCheckList":1,"opcaoItemCheckList":1},{"idCabecItemCheckList":1,"idItItemCheckList":33749,"idItemCheckList":2,"opcaoItemCheckList":1},{"idCabecItemCheckList":1,"idItItemCheckList":33750,"idItemCheckList":3,"opcaoItemCheckList":1}]}';
    
    $dados = $info['dado'];
    $inserirDadosDAO->salvarDados($dados, "apontchecklistdt");
    $posicao = strpos($dados, "_") + 1;
    $cabec = substr($dados, 0, ($posicao - 1));
    $item = substr($dados, $posicao);

    $jsonObjCabec = json_decode($cabec);
    $jsonObjItem = json_decode($item);
    $dadosCab = $jsonObjCabec->cabecalho;
    $dadosItem = $jsonObjItem->item;
    
    $retorno = $apontarCheckListDAO->salvarDados($dadosCab, $dadosItem);

endif;

echo 'GRAVOU-CHECKLIST';
