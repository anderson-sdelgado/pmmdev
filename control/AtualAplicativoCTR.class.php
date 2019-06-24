<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require('./model/dao/AtualAplicativoDAO.class.php');
/**
 * Description of AtualAplicativoCTR
 *
 * @author anderson
 */
class AtualAplicativoCTR {
    //put your code here
    
    public function verAtualAplic($info) {

        $atualAplicativoDAO = new AtualAplicativoDAO();

        $jsonObj = json_decode($info['dado']);
        $dados = $jsonObj->dados;
        $dadosAtualAplic = $atualAplicativoDAO->verAtualAplic($dados);
        return $dadosAtualAplic;
        
    }
    
    public function verAtualAplicVersao1($info) {

        $atualAplicativoDAO = new AtualAplicativoDAO();

        $jsonObj = json_decode($info['dado']);
        $dados = $jsonObj->dados;
        $dadosAtualAplic = $atualAplicativoDAO->verAtualAplicVersao1($dados);
        return $dadosAtualAplic;
        
    }
    
}
