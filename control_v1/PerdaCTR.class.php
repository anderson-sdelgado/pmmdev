<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('./model_v1/dao/PerdaDAO.class.php');
/**
 * Description of PerdaCTR
 *
 * @author anderson
 */
class PerdaCTR {

    //put your code here

    public function dados($info) {

        $perdaDAO = new PerdaDAO();

        $dado = $info['dado'];

        $dadosPerda = array("dados" => $perdaDAO->dados($dado));
        $resPerda = json_encode($dadosPerda);
        
        return $resPerda;
        
    }

}
