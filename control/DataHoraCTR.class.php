<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require('./model/dao/DataHoraDAO.class.php');
/**
 * Description of DataHoraCTR
 *
 * @author anderson
 */
class DataHoraCTR {
    //put your code here
    
    public function dados() {
        
        $dataHoraDAO = new DataHoraDAO();
       
        $dados = array("dados"=>$dataHoraDAO->dados());
        $json_str = json_encode($dados);
        
        return $json_str;
        
    }
    
}
