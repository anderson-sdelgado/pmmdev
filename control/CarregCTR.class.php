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

    public function salvarDados($info) {
        
        $dados = $info['dado'];
        $jsonObjCarreg  = json_decode($dados);
        $dadosCarreg = $jsonObjCarreg->carreg;
        $this->salvarDadosCarregInsumo($dadosCarreg);
        
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
    
    private function salvarDadosCarregInsumo($dadosCarreg) {
        
        $carregDAO = new CarregDAO();
        $idCarregArray = array();
        foreach ($dadosCarreg as $carreg) {
            $v = $carregDAO->verifCarregProd($carreg);
            if ($v == 0) {
                $carregDAO->cancelCarregProd($carreg);
                $carregDAO->insCarregProd($carreg);
            }
            $idCarregArray[] = array("idCarreg" => $carreg->idCarreg);
        }
        $dadoCarreg = array("dados"=>$idCarregArray);
        $retCarreg = json_encode($dadoCarreg);

        echo 'GRAVOU-CARREGINSUMO_' . $retCarreg;
        
    }
   
}
