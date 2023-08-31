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
            $idEquip = $d->idEquip;
            $idCheckList = $d->idCheckList;
            $token = $d->token;
        }
        
        $v = $atualAplicDAO->verToken($token);
        
        if ($v > 0) {
            
            $retAtualApp = 0;
            $retAtualCheckList = 0;

            $result = $atualAplicDAO->verAtualCheckList($idEquip);
            foreach ($result as $item) {
                $verCheckList = $item['VERIF_CHECKLIST'];
            }
            if ($verCheckList == 1) {
                $retAtualCheckList = 1;
            }
            $idCheckListBD = $atualAplicDAO->idCheckList($idEquip);
            if ($idCheckList != $idCheckListBD) {
                $retAtualCheckList = 1;
            }
                
            $dthr = $atualAplicDAO->dataHora();
            $dado = array("flagAtualApp" => $retAtualApp, "flagAtualCheckList" => $retAtualCheckList
                , "dthr" => $dthr);

            return json_encode(array("dados" =>array($dado)));
        
        }

    }
    
    public function inserirAtualVersao($idEquip, $versao) {
        $atualAplicDAO = new AtualAplicDAO();
        $v = $atualAplicDAO->verAtual($idEquip);
        if ($v == 0) {
            $atualAplicDAO->insAtual($idEquip, $versao);
        } else {
            $atualAplicDAO->updAtual($idEquip, $versao);
        }
    }

    public function verifToken($info){
        
        $jsonObj = json_decode($info['dado']);
        $dados = $jsonObj->dados;

        foreach ($dados as $d) {
            $token = $d->token;
        }
        
        $atualAplicDAO = new AtualAplicDAO();
        $v = $atualAplicDAO->verToken($token);
        
        if ($v > 0) {
            return true;
        } else {
            return false;
        }
        
    }
}
