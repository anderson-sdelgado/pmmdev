<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/dao/PerdaDAO.class.php');
/**
 * Description of PerdaCTR
 *
 * @author anderson
 */
class PerdaCTR {

    //put your code here

    public function dados($versao, $info) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 2.00){
        
            $dado = $info['dado'];

            $perdaDAO = new PerdaDAO();
            
            $dadosPerda = array("dados" => $perdaDAO->dados($dado));
            $resPerda = json_encode($dadosPerda);

            return $resPerda;
        
        }
        
    }

}
