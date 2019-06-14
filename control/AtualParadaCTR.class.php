<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('./model/dao/RAtivParadaDAO.class.php');
require_once('./model/dao/ParadaDAO.class.php');
/**
 * Description of AtualParada
 *
 * @author anderson
 */
class AtualParadaCTR {

    //put your code here

    public function dados() {

        $rAtivParadaDAO = new RAtivParadaDAO();
        $paradaDAO = new ParadaDAO();

        $dadosRAtivParadaDAO = array("dados" => $rAtivParadaDAO->dados());
        $resRAtivParadaDAO = json_encode($dadosRAtivParadaDAO);

        $dadosParada = array("dados" => $paradaDAO->dados());
        $resParada = json_encode($dadosParada);
        
        return $resRAtivParadaDAO . "_" . $resParada;
                
    }
    
    public function dadosVersao1($info) {

        $rAtivParadaDAO = new RAtivParadaDAO();

        $dado = $info['dado'];
        
        $dadosRAtivParadaDAO = array("dados" => $rAtivParadaDAO->dadosVersao1($dado));
        $resRAtivParadaDAO = json_encode($dadosRAtivParadaDAO);
        
        return $resRAtivParadaDAO;
                
    }

}
