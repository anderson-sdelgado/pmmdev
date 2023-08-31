<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/AtualAplicDAO.class.php');
require_once('../model/CECDAO.class.php');
require_once('../model/PreCECDAO.class.php');
/**
 * Description of CECCTR
 *
 * @author anderson
 */
class CECCTR {

    public function buscarCEC($info) {
        
        $atualAplicDAO = new AtualAplicDAO();

        $dados = $info['dado'];
        $array = explode("_",$dados);
        
        $jsonObjEquip = json_decode($array[0]);
        $jsonObjPreCEC = json_decode($array[1]);
        $jsonObjToken = json_decode($array[2]);
        
        $dadosToken = $jsonObjToken->dados;

        foreach ($dadosToken as $d) {
            $token = $d->token;
        }

        $v = $atualAplicDAO->verToken($token);
        
        if ($v > 0) {
            
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
            $idPreCECArray[] = array("idPreCEC" =>0);
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
