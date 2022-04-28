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

    public function salvarDados($info) {

        $dados = $info['dado'];
        $jsonObjPreCEC = json_decode($dados);
        $dadosPreCEC = $jsonObjPreCEC->precec;
        $preCECDAO = new PreCECDAO();
        $idPreCECArray = array();

        foreach ($dadosPreCEC as $precec) {
            $v = $preCECDAO->verifPreCEC($precec);
            if ($v == 0) {
                $preCECDAO->insPreCEC($precec);
            }
            $idPreCECArray[] = array("idPreCEC" => $precec->idPreCEC);
        }

        $dadoPreCEC = array("precec"=>$idPreCECArray);
        $retPreCEC = json_encode($dadoPreCEC);

        return 'PRECEC_' . $retPreCEC;
        
    }

}
