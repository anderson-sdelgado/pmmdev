<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/CarregDAO.class.php');
/**
 * Description of CarregCTR
 *
 * @author anderson
 */
class CarregCTR {
    
    private $base = 2;
    
    public function salvarDados($versao, $info, $pagina) {
        
        $dados = $info['dado'];
        $pagina = $pagina . '-' . $versao;

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 2.00){
        
            $jsonObj = json_decode($dados);
            $carreg = $jsonObj->carreg;
            $this->salvarDadosCarreg($carreg);
        
        }
        
    }
    
    public function atualLeiraDescarreg($versao, $info, $pagina){
        
        $dados = $info['dado'];
        $pagina = $pagina . '-' . $versao;

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 2.00){
        
            $jsonObj = json_decode($dados);
            $carreg = $jsonObj->carreg;
            $this->updLeiraDescarreg($carreg);
        
        }
        
    }
    
    private function salvarDadosCarreg($carreg) {
        
        $carregDAO = new CarregDAO();
        $idCarregArray = array();
        $tipo = 0;
        foreach ($carreg as $c) {
            $tipo = $c->tipoCarreg;
            if ($c->tipoCarreg == 1) {
                $v = $carregDAO->verifCarregProd($c, $this->base);
                if ($v == 0) {
                    $carregDAO->cancelCarregProd($c, $this->base);
                    $carregDAO->insCarregProd($c, $this->base);
                }
            } elseif ($c->tipoCarreg == 2) {
                $v = $carregDAO->verifCarregComp($c, $this->base);
                if ($v == 0) {
                    $carregDAO->cancelCarregComp($c, $this->base);
                    $carregDAO->insCarregComp($c, $this->base);
                }
            }
            $idCarregArray[] = array("idCarreg" => $c->idCarreg);
        }
        $dadoCarreg = array("dados"=>$idCarregArray);
        $retCarreg = json_encode($dadoCarreg);
        if($tipo == 1){
            echo 'GRAVOU-CARREGINSUMO_' . $retCarreg;
        } elseif ($tipo == 2) {
            echo 'GRAVOU-CARREGCOMPOSTO_' . $retCarreg;
        }
        
        
    }
    
    private function updLeiraDescarreg($carreg) {
        
        $carregDAO = new CarregDAO();
        $idCarregArray = array();
        foreach ($carreg as $c) {
            $carregDAO->updLeiraDescarreg($c, $this->base);
            $idCarregArray[] = array("idCarreg" => $c->idCarreg);
        }
        $dadoCarreg = array("dados"=>$idCarregArray);
        $retCarreg = json_encode($dadoCarreg);
        echo 'GRAVOU-CARREGCOMPOSTO_' . $retCarreg;
//        echo 'GRAVOU-LEIRADESCARREG_' . $retCarreg;
        
    }
    
    public function pesqLeiraComp($versao, $info) {

        $versao = str_replace("_", ".", $versao);
        
        $carregDAO = new CarregDAO();
        
        if ($versao >= 2.00) {
        
            $jsonObj = json_decode($info['dado']);
            $dados = $jsonObj->dados;

            foreach ($dados as $d) {
                $equip = $d->equipCarreg;
                $os = $d->osCarreg;
            }

            $retorno = array("dados" => $carregDAO->retLeiraComp($equip, $os, $this->base));
            $ret = json_encode($retorno);
            return $ret;
        
        }
        
    }
    
    public function retCarreg($versao, $info) {

        $versao = str_replace("_", ".", $versao);

        $carregDAO = new CarregDAO();

        if ($versao >= 2.00) {
            $retorno = array("dados" => $carregDAO->retCarreg($info['dado'], $this->base));
            $ret = json_encode($retorno);
            return $ret;
        }
    }
   
}
