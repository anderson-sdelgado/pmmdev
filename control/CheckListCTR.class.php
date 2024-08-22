<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../control/AtualAplicCTR.class.php');
require_once('../model/AtualAplicDAO.class.php');
require_once('../model/EquipDAO.class.php');
require_once('../model/ItemCheckListDAO.class.php');
require_once('../model/CabecCheckListDAO.class.php');
require_once('../model/RespCheckListDAO.class.php');
/**
 * Description of CheckListCTR
 *
 * @author anderson
 */
class CheckListCTR {

    public function pesq($info) {
        
        $equipDAO = new EquipDAO();
        $itemCheckListDAO = new ItemCheckListDAO();
        $atualAplicDAO = new AtualAplicDAO();

        $jsonObj = json_decode($info['dado']);
        $dados = $jsonObj->dados;

        foreach ($dados as $d) {
            $nroEquip = $d->nroEquip;
            $token = $d->token;
        }
        
        $v = $atualAplicDAO->verToken($token);
        
        if ($v > 0) {

            $dadosEquip = array("dados" => $equipDAO->dados($nroEquip));
            $resEquip = json_encode($dadosEquip);

            $dadosItemCheckList = array("dados" => $itemCheckListDAO->dados());
            $resItemCheckList = json_encode($dadosItemCheckList);

            $itemCheckListDAO->atualCheckList($nroEquip);

            return $resEquip . "_" . $resItemCheckList;
        
        }

    }
    
    public function dadosItem($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){
        
            $itemCheckListDAO = new ItemCheckListDAO();

            $dados = array("dados"=>$itemCheckListDAO->dados());
            $json_str = json_encode($dados);

            return $json_str;
        
        }
        
    }
    
    public function salvarDados($body) {

        $cabecArray = json_decode($body);
        $this->salvarBoletim($cabecArray);
        return $body;

    }
    
    private function salvarBoletim($cabecArray) {
        $cabecCheckListDAO = new CabecCheckListDAO();
        foreach ($cabecArray as $cabec) {
            $v = $cabecCheckListDAO->verifCabecCheckList($cabec);
            if ($v == 0) {
                $cabecCheckListDAO->insCabecCheckList($cabec);
            }
            $idCabec = $cabecCheckListDAO->idCabecCheckList($cabec);
            $this->salvarApont($idCabec, $cabec->idCabCL, $cabec->respItemCheckListList);
        }
    }
    
    private function salvarApont($idBolBD, $idBolCel, $dadosItem) {
        $respCheckListDAO = new RespCheckListDAO();
        foreach ($dadosItem as $i) {
            if ($idBolCel == $i->idCabItCL) {
                $v = $respCheckListDAO->verifRespCheckList($idBolBD, $i);
                if ($v == 0) {
                    $respCheckListDAO->insRespCheckList($idBolBD, $i);
                }
            }
        }
    }
    
}
