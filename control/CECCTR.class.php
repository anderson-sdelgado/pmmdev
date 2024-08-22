<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/AtualAplicDAO.class.php');
require_once('../model/CECDAO.class.php');
require_once('../model/PreCECDAO.class.php');
/**
 * Description of CECCTR
 *
 * @author anderson
 */
class CECCTR {

    public function buscarCEC($body) {
        
        $config = json_decode($body);
        return $this->pesquisar($config);

    }

    private function pesquisar($config){

        $cecDAO = new CECDAO();
        $dadoCEC = $cecDAO->pesqCEC($config->equipConfig);
        $cec = $dadoCEC[0];
        $cecDAO->deleteCEC($cec["caminhaoCEC"]);

        return json_encode($cec, JSON_NUMERIC_CHECK);
        
    }
    
}
