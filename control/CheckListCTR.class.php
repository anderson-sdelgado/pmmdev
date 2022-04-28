<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
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

        $nroEquip = $info['dado'];

        $equipDAO = new EquipDAO();
        $itemCheckListDAO = new ItemCheckListDAO();

        $dadosEquip = array("dados" => $equipDAO->dados($nroEquip));
        $resEquip = json_encode($dadosEquip);

        $dadosItemCheckList = array("dados" => $itemCheckListDAO->dados());
        $resItemCheckList = json_encode($dadosItemCheckList);

        $itemCheckListDAO->atualCheckList($nroEquip);

        return $resEquip . "_" . $resItemCheckList;

    }
    
    public function dadosItem() {

        $itemCheckListDAO = new ItemCheckListDAO();

        $dados = array("dados"=>$itemCheckListDAO->dados());
        $json_str = json_encode($dados);

        return $json_str;
        
    }
    
    public function salvarDados($info) {

        $dados = $info['dado'];
        
        $posicao = strpos($dados, "_") + 1;
        $cabec = substr($dados, 0, ($posicao - 1));
        $item = substr($dados, $posicao);

        $jsonObjCabec = json_decode($cabec);
        $jsonObjItem = json_decode($item);

        $dadosCab = $jsonObjCabec->cabecalho;
        $dadosItem = $jsonObjItem->item;

        $this->salvarBoletim($dadosCab, $dadosItem);

        return 'GRAVOU-CHECKLIST';
                
    }
    
    private function salvarBoletim($dadosCab, $dadosItem) {
        $cabecCheckListDAO = new CabecCheckListDAO();
        foreach ($dadosCab as $d) {
            $v = $cabecCheckListDAO->verifCabecCheckList($d);
            if ($v == 0) {
                $cabecCheckListDAO->insCabecCheckList($d);
            }
            $idCabec = $cabecCheckListDAO->idCabecCheckList($d);
            $this->salvarApont($idCabec, $d->idCabCL, $dadosItem);
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
