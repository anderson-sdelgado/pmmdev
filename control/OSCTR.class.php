<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/dao/OSDAO.class.php');
require_once('../model/dao/ROSAtivDAO.class.php');
/**
 * Description of OSCTR
 *
 * @author anderson
 */
class OSCTR {
    //put your code here
    
    public function dados($versao, $info) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 2.00){
        
            $osDAO = new OSDAO();
            $rOSAtivDAO = new ROSAtivDAO();

            $dado = $info['dado'];

            $dadosOS = array("dados" => $osDAO->dados($dado));
            $resOS = json_encode($dadosOS);

            $dadosROSAtiv = array("dados" => $rOSAtivDAO->dados($dado));
            $resROSAtiv = json_encode($dadosROSAtiv);

            return $resOS . "#" . $resROSAtiv;
        
        }
        
    }
    
    public function pesqECM($versao, $info) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 2.00){
        
            $osDAO = new OSDAO();
            
            $dado = $info['dado'];

            $dadosOS = array("dados" => $osDAO->dadosECM($dado));
            $resOS = json_encode($dadosOS);

            return $resOS;
        
        }
        
    }
    
    public function dadosECM($versao) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 2.00){
        
            $osDAO = new OSDAO();
            
            $dadosOS = array("dados" => $osDAO->dadosClearECM());
            $resOS = json_encode($dadosOS);

            return $resOS;
        
        }
        
    }
    
}
