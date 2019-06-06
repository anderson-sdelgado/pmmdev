<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require('./model/dao/CabecCheckListDAO.class.php');
require('./model/dao/RespCheckListDAO.class.php');
require('./model/dao/InserirLogDAO.class.php');

/**
 * Description of InserirDadosCheckListCTR
 *
 * @author anderson
 */
class InserirCheckListCTR {

    //put your code here

    public function salvarDados($info) {

        $inserirLogDAO = new InserirLogDAO();
        $cabecCheckListDAO = new CabecCheckListDAO();
        $respCheckListDAO = new RespCheckListDAO();

        $dados = $info['dado'];
        $inserirLogDAO->salvarDados($dados, "inserirchecklist2");
        $posicao = strpos($dados, "_") + 1;
        $cabec = substr($dados, 0, ($posicao - 1));
        $item = substr($dados, $posicao);

        $jsonObjCabec = json_decode($cabec);
        $jsonObjItem = json_decode($item);
        $dadosCab = $jsonObjCabec->cabecalho;
        $dadosItem = $jsonObjItem->item;

        foreach ($dadosCab as $d) {
            $v = $cabecCheckListDAO->verifCabecCheckList($d);
            if ($v == 0) {
                $cabecCheckListDAO->insCabecCheckList($d);
                $idCabec = $cabecCheckListDAO->idCabecCheckList($d);
                foreach ($dadosItem as $i) {
                    if ($d->idCab == $i->idCabIt) {
                        $v = $respCheckListDAO->verifRespCheckList($idCabec, $i);
                        if ($v == 0) {
                            $respCheckListDAO->insRespCheckList($idCabec, $i);
                        }
                    }
                }
            } else {
                $idCabec = $cabecCheckListDAO->idCabecCheckList($d);
                foreach ($dadosItem as $i) {
                    if ($d->idCab == $i->idCabIt) {
                        $v = $respCheckListDAO->verifRespCheckList($idCabec, $i);
                        if ($v == 0) {
                            $respCheckListDAO->insRespCheckList($idCabec, $i);
                        }
                    }
                }
            }
        }
    }

}
