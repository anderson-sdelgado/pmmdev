<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/dao/PneuDAO.class.php');
/**
 * Description of PneuCTR
 *
 * @author anderson
 */
class PneuCTR {
    //put your code here
    
    public function dados($versao, $info) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 2.00){
        
            $pneuDAO = new PneuDAO();

            $dado = $info['dado'];

            $dadosOS = array("dados" => $pneuDAO->dados($dado));
            $resOS = json_encode($dadosOS);

            return $resOS;
        
        }
        
    }
    
}
