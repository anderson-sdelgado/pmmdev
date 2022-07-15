<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/AtualAplicDAO.class.php');
/**
 * Description of AtualAplicativoCTR
 *
 * @author anderson
 */
class AtualAplicCTR {
    
    public function atualAplic($info) {

        $atualAplicDAO = new AtualAplicDAO();

        $jsonObj = json_decode($info['dado']);
        $dados = $jsonObj->dados;

        foreach ($dados as $d) {
            $equip = $d->idEquipAtual;
            $versaoAtual = $d->versaoAtual;
            $checkList = $d->idCheckList;
            $checkListBD = $d->idCheckList;
        }

        $retAtualApp = 0;
        $retAtualCheckList = 0;

        $v = $atualAplicDAO->verAtual($equip);
        if ($v == 0) {
            $atualAplicDAO->insAtual($equip, $versaoAtual);
        } else {
            $result = $atualAplicDAO->retAtual($equip);
            foreach ($result as $item) {
                $versaoNova = $item['VERSAO_NOVA'];
                $versaoAtualBD = $item['VERSAO_ATUAL'];
            }
            if ($versaoAtual != $versaoAtualBD) {
                $atualAplicDAO->updAtualNova($equip, $versaoAtual);
            } else {
                if ($versaoAtual != $versaoNova) {
                    $retAtualApp = 1;
                } else {
                    $result = $atualAplicDAO->verAtualCheckList($equip);
                    $versaoAtualBD = '';
                    foreach ($result as $item) {
                        $versaoAtualBD = $item['VERSAO_ATUAL'];
                        $verCheckList = $item['VERIF_CHECKLIST'];
                    }
                    if (strcmp($versaoAtual, $versaoAtualBD) <> 0) {
                        $atualAplicDAO->updAtual($equip, $versaoAtual);
                    } else {
                        if ($verCheckList == 1) {
                            $retAtualCheckList = 1;
                        }
                    }
                    $checkListBD = $atualAplicDAO->idCheckList($equip);
                    if ($checkList != $checkListBD) {
                        $retAtualCheckList = 1;
                    }
                }
            }
        }
        $atualAplicDAO->updUltAcesso($equip);
        $dthr = $atualAplicDAO->dataHora();
        $dado = array("flagAtualApp" => $retAtualApp, "flagAtualCheckList" => $retAtualCheckList
            , "dthr" => $dthr);

        return json_encode(array("dados" =>array($dado)));

    }

}
