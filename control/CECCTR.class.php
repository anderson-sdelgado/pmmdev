<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/dao/CECDAO.class.php');
require_once('../model/dao/PreCECDAO.class.php');
/**
 * Description of CECCTR
 *
 * @author anderson
 */
class CECCTR {

    //put your code here
    
    public function buscarCEC($versao, $info) {

        $dados = $info['dado'];
        $versao = str_replace("_", ".", $versao);

        if ($versao >= 2.00) {

            $pos1 = strpos($dados, "_") + 1;

            $equip = substr($dados, 0, ($pos1 - 1));
            $precec = substr($dados, $pos1);

            $jsonObjEquip = json_decode($equip);
            $jsonObjPreCEC = json_decode($precec);

            $dadosEquip = $jsonObjEquip->equip;
            $dadosPreCEC = $jsonObjPreCEC->precec;

            $ret = $this->pesquisar($dadosEquip, $dadosPreCEC);
            
            return $ret;
        }
        
    }

    private function pesquisar($dadosEquip, $dadosPreCEC){
            
        $preCECDAO = new PreCECDAO();
        $idPreCECArray = array();

        $ver = true;
        foreach ($dadosPreCEC as $precec) {
            $v = $preCECDAO->verifPreCEC($precec);
            if ($v == 0) {
                $preCECDAO->insPreCEC($precec);
            }
            $idPreCECArray[] = array("idPreCEC" => $precec->idPreCEC);
            $ver = false;
        }

        if($ver == true){
            $idPreCECArray[] = array("idPreCEC" => 0);
        }
        
        $dadoPreCEC = array("precec"=>$idPreCECArray);
        $retPreCEC = json_encode($dadoPreCEC);
        
        foreach ($dadosEquip as $equip) {
            $cecDAO = new CECDAO();
            $dadoCEC = $cecDAO->pesqCEC($equip->nroEquip);
            $cecDAO->deleteCEC($equip->nroEquip);
        }
        
        $dadosCEC = array("cec" => $dadoCEC);
        $retCEC = json_encode($dadosCEC);
        
        return $retPreCEC . '_' . $retCEC;
        
    }
    
    
}
