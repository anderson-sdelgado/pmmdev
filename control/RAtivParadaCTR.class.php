<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require('./model/dao/RAtivParadaDAO.class.php');
/**
 * Description of RAtivParadaDAO
 *
 * @author anderson
 */
class RAtivParadaCTR {
    //put your code here
    
    public function dados() {
        
        $rAtivParadaDAO = new RAtivParadaDAO();
       
        $dados = array("dados"=>$rAtivParadaDAO->dados());
        $json_str = json_encode($dados);
        
        return $json_str;
        
    }
    
}
