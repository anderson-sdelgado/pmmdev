<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/AtualAplicDAO.class.php');
require_once('../model/CarregDAO.class.php');
/**
 * Description of CarregCTR
 *
 * @author anderson
 */
class CarregCTR {

    public function salvarDados($body) {
        
        $carreg  = json_decode($body);
        $this->salvarDadosCarregInsumo($carreg);
        return $body;
        
    }

    public function retCarreg($info) {

        $carregDAO = new CarregDAO();
        $atualAplicDAO = new AtualAplicDAO();

        $jsonObj = json_decode($info['dado']);
        $dados = $jsonObj->dados;

        foreach ($dados as $d) {
            $idEquip = $d->idEquip;
            $token = $d->token;
        }

        $v = $atualAplicDAO->verToken($token);
        
        if ($v > 0) {
            
            $retorno = array("dados" => $carregDAO->retCarreg($idEquip));
            $ret = json_encode($retorno);
            return $ret;
            
        }

    }
    
    private function salvarDadosCarregInsumo($carreg) {
        
        $carregDAO = new CarregDAO();

        $v = $carregDAO->verifCarregProd($carreg);
        if ($v == 0) {
            $carregDAO->cancelCarregProd($carreg);
            $carregDAO->insCarregProd($carreg);
        }
        
    }
   
}
