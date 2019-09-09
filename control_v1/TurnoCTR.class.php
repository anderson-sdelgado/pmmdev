<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('./model_v1/dao/TurnoDAO.class.php');
/**
 * Description of TurnoCTR
 *
 * @author anderson
 */
class TurnoCTR {
    //put your code here
    
    public function dados() {
        
        $turnoDAO = new TurnoDAO();
       
        $dados = array("dados"=>$turnoDAO->dados());
        $json_str = json_encode($dados);
        
        return $json_str;
        
    }
    
}
