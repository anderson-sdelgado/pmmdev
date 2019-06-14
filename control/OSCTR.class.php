<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require('./model/dao/OSDAO.class.php');
require('./model/dao/ROSAtivDAO.class.php');
/**
 * Description of OSCTR
 *
 * @author anderson
 */
class OSCTR {
    //put your code here
    
    public function dados($info) {

        $osDAO = new OSDAO();
        $rOSAtivDAO = new ROSAtivDAO();

        $dado = $info['dado'];

        $dadosOS = array("dados" => $osDAO->dados($dado));
        $resOS = json_encode($dadosOS);

        $dadosROSAtiv = array("dados" => $rOSAtivDAO->dados($dado));
        $resROSAtiv = json_encode($dadosROSAtiv);
        
        return $resOS . "#" . $resROSAtiv;
                
    }
    
    public function dadosVersao1($info) {

        $osDAO = new OSDAO();

        $dado = $info['dado'];
        
        $dadosOS = array("dados" => $osDAO->dados($dado));
        $resOS = json_encode($dadosOS);
        
        return $resOS;
                
    }
    
    public function ver($info) {

        $osDAO = new OSDAO();
        $rOSAtivDAO = new ROSAtivDAO();

        $dado = $info['dado'];

        $dadosOS = array("dados" => $osDAO->dados($dado));
        $resOS = json_encode($dadosOS);

        $dadosROSAtiv = array("dados" => $rOSAtivDAO->dados($dado));
        $resROSAtiv = json_encode($dadosROSAtiv);
        
        return $resOS . "_" . $resROSAtiv;
                
    }
    
}
