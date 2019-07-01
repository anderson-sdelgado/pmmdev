<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require('./model/dao/AtualAplicDAO.class.php');
/**
 * Description of AtualAplicativoCTR
 *
 * @author anderson
 */
class AtualAplicCTR {
    //put your code here
    
    public function verAtualAplic($info) {

        $atualAplicDAO = new AtualAplicDAO();

        $jsonObj = json_decode($info['dado']);
        $dados = $jsonObj->dados;
        $dadosAtualAplic = $atualAplicDAO->verAtualAplic($dados);
        return $dadosAtualAplic;
        
    }
    
    public function verAtualAplicVersao1($info) {

        $atualAplicDAO = new AtualAplicDAO();

        $jsonObj = json_decode($info['dado']);
        $dados = $jsonObj->dados;
        $dadosAtualAplic = $atualAplicDAO->verAtualAplicVersao1($dados);
        return $dadosAtualAplic;
        
    }
    
}
