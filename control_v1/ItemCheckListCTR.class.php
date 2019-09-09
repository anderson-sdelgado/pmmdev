<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('./model_v1/dao/ItemCheckListDAO.class.php');
/**
 * Description of ItemCheckListCTR
 *
 * @author anderson
 */
class ItemCheckListCTR {
    //put your code here
    
    public function dados() {
        
        $itemCheckListDAO = new ItemCheckListDAO();
       
        $dados = array("dados"=>$itemCheckListDAO->dados());
        $json_str = json_encode($dados);
        
        return $json_str;
        
    }
    
}
