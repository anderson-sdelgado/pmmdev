<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/dao/RAtivParadaDAO.class.php');
require_once('../model/dao/ParadaDAO.class.php');
/**
 * Description of ParadaCTR
 *
 * @author anderson
 */
class ParadaCTR {
    //put your code here

    public function dados($versao) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 2.00){
        
            $paradaDAO = new ParadaDAO();

            $dados = array("dados" => $paradaDAO->dados());
            $json_str = json_encode($dados);

            return $json_str;
        
        }
        
    }
    
    public function atual($versao) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 2.00){
            
            $rAtivParadaDAO = new RAtivParadaDAO();
            $paradaDAO = new ParadaDAO();

            $dadosRAtivParadaDAO = array("dados" => $rAtivParadaDAO->dados());
            $resRAtivParadaDAO = json_encode($dadosRAtivParadaDAO);

            $dadosParada = array("dados" => $paradaDAO->dados());
            $resParada = json_encode($dadosParada);

            return $resRAtivParadaDAO . "_" . $resParada;
        
        }
                
    }
    
}
