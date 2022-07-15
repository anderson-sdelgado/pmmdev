<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/dao/LogProcessoDAO.class.php');
/**
 * Description of LogErroCTR
 *
 * @author anderson
 */
class LogProcessoCTR {
    //put your code here
    
    public function salvar($info) {

        $dados = $info['dado'];

        $jsonObj = json_decode($dados);
        $logProcesso = $jsonObj->logprocesso;
        $this->salvarLogProcesso($logProcesso);
        
    }
    
    private function salvarLogProcesso($dadosLogProcesso) {
        $logProcessoDAO = new LogProcessoDAO();
        foreach ($dadosLogProcesso as $logProcesso) {
            $logProcessoDAO->insLogErro($logProcesso);
        }

    }
    
}
