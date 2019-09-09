<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/dao/BocalDAO.class.php');
/**
 * Description of Bocal
 *
 * @author anderson
 */
class BocalCTR {
    //put your code here
    
    public function dados($versao) {
        
        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 2.00){
        
            $bocalDAO = new BocalDAO();

            $dados = array("dados"=>$bocalDAO->dados());
            $json_str = json_encode($dados);

            return $json_str;
        
        }
        
    }
    
}
