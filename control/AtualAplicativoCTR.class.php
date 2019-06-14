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

        $dados = $info['dado'];
        $dadosAtualAplic = array("dados" => $atualAplicativoDAO->verAtualAplic($dados));
        return $dadosAtualAplic;
        
    }
    
    public function verAtualAplicVersao1($info) {

        $atualAplicativoDAO = new AtualAplicativoDAO();

        $dados = $info['dado'];
        $dadosAtualAplic = array("dados" => $atualAplicativoDAO->verAtualAplic($dados));
        return $dadosAtualAplic;
        
    }
    
}
