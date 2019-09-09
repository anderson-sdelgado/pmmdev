<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('./model_v1/dao/CheckListDAO.class.php');
/**
 * Description of CheckListCTR
 *
 * @author anderson
 */
class CheckListCTR {
    //put your code here
    
    public function dados() {
        
        $checkListDAO = new CheckListDAO();
       
        $dados = array("dados"=>$checkListDAO->dados());
        $json_str = json_encode($dados);
        
        return $json_str;
        
    }
    
}
