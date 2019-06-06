<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require('./model/dao/PressaoBocalDAO.class.php');
/**
 * Description of PressaoBocal
 *
 * @author anderson
 */
class PressaoBocalCTR {
    //put your code here
    
    public function dados() {

        $pressaoBocalDAO = new PressaoBocalDAO();

        $dados = array("dados" => $pressaoBocalDAO->dados());
        $json_str = json_encode($dados);

        return $json_str;
        
    }
    
}
