<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('./model_v1/dao/MotoristaDAO.class.php');
/**
 * Description of MotoristaCTR
 *
 * @author anderson
 */
class MotoristaCTR {
    //put your code here

    public function dados() {

        $motoristaDAO = new MotoristaDAO();

        $dados = array("dados" => $motoristaDAO->dados());
        $json_str = json_encode($dados);

        return $json_str;
        
    }
    
}
