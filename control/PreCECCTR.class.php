<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/PreCECDAO.class.php');
/**
 * Description of CecCTR
 *
 * @author anderson
 */
class PreCECCTR {

    public function salvarDados($body) {

        $precec = json_decode($body);
        $preCECDAO = new PreCECDAO();

        $v = $preCECDAO->verifPreCEC($precec);
        if ($v == 0) {
            $preCECDAO->insPreCEC($precec);
        }

        return $body;
        
    }

}
