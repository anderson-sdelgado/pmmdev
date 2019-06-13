<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('./model/dao/AtividadeDAO.class.php');
/**
 * Description of AtividadeCTR
 *
 * @author anderson
 */
class AtividadeCTR {
    //put your code here
    
    public function dados() {
        
        $atividadeDAO = new AtividadeDAO();
       
        $dados = array("dados"=>$atividadeDAO->dados());
        $json_str = json_encode($dados);
        
        return $json_str;
        
    }
    
}
