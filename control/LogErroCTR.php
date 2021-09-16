<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/dao/LogErroDAO.class.php');
/**
 * Description of LogErroCTR
 *
 * @author anderson
 */
class LogErroCTR {
    
    private $base = 2;
    
    public function salvarLog($versao, $info) {

        $dados = $info['dado'];
        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 3.00){
        
            $jsonObj = json_decode($dados);
            $logErro = $jsonObj->logerro;
            $ret = $this->salvarLogErro($logErro);
            
            return $ret;
        
        }
        
    }
    
    private function salvarLogErro($dadosLogErro) {
        $logErroDAO = new LogErroDAO();
        $idLogArray = array();
        foreach ($dadosLogErro as $logErro) {
            $v = $logErroDAO->verifLogErro($logErro, $this->base);
            if ($v == 0) {
                $logErroDAO->insLogErro($logErro, $this->base);
            }
            $idLogArray[] = array("idLog" => $logErro->idLog);
        }
        $dadoLog = array("dados"=>$idLogArray);
        $retLog = json_encode($dadoLog);
        
        return 'LOGERRO_' . $retLog;
    }
    
}
