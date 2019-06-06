<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require('./model/dao/PneuDAO.class.php');
/**
 * Description of PneuCTR
 *
 * @author anderson
 */
class PneuCTR {
    //put your code here
    
    public function dados($info) {

        $pneuDAO = new PneuDAO();

        $dado = $info['dado'];

        $dadosPneu = array("dados" => $pneuDAO->dados($dado));
        $resPneu = json_encode($dadosPneu);
        
        return $resPneu;
                
    }
    
}
