<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/dao/PreCECDAO.class.php');
require_once('../model/dao/LogDAO.class.php');
/**
 * Description of CecCTR
 *
 * @author anderson
 */
class PreCECCTR {

    private $base = 2;
    
    public function salvarDados($versao, $info, $pagina) {

        $dados = $info['dado'];
        $pagina = $pagina . '-' . $versao;
        $this->salvarLog($dados, $pagina);

        $versao = str_replace("_", ".", $versao);

        if ($versao >= 2.00) {

            $jsonObjPreCEC = json_decode($dados);
            $dadosPreCEC = $jsonObjPreCEC->precec;
            $preCECDAO = new PreCECDAO();
            $idPreCECArray = array();
            
            foreach ($dadosPreCEC as $precec) {
                $v = $preCECDAO->verifPreCEC($precec, $this->base);
                if ($v == 0) {
                    $preCECDAO->insPreCEC($precec, $this->base);
                }
                $idPreCECArray[] = array("idPreCEC" => $precec->idPreCEC);
            }
            
            $dadoPreCEC = array("precec"=>$idPreCECArray);
            $retPreCEC = json_encode($dadoPreCEC);
            
            return 'PRECEC_' . $retPreCEC;
        }
    }
    
    private function salvarLog($dados, $pagina) {
        $logDAO = new LogDAO();
        $logDAO->salvarDados($dados, $pagina, $this->base);
    }
    
}
