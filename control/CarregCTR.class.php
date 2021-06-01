<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/dao/LogDAO.class.php');
require_once('../model/dao/CarregDAO.class.php');
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
        $this->salvarLog($dados, $pagina);

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 2.00){
        
            $jsonObj = json_decode($dados);
            $carreg = $jsonObj->carreg;
            $this->salvarDadosCarreg($carreg);
        
        }
        
    }
    
    private function salvarDadosCarreg($carreg) {
        
        $carregDAO = new CarregDAO();
        $idCarregArray = array();
        foreach ($carreg as $c) {
            if ($c->tipoCarreg == 1) {
                $carregDAO->cancelCarregProd($c, $this->base);
                $v = $carregDAO->verifCarregProd($c, $this->base);
                if ($v == 0) {
                    $carregDAO->insCarregProd($c, $this->base);
                }
            } elseif ($c->tipoCarreg == 2) {
                $carregDAO->cancelCarregComp($c, $this->base);
                $v = $carregDAO->verifCarregComp($c, $this->base);
                if ($v == 0) {
                    $carregDAO->insCarregComp($c, $this->base);
                }
            }
            $idCarregArray[] = array("idCarreg" => $c->idCarreg);
        }
        $dadoCarreg = array("carreg"=>$idCarregArray);
        $retCarreg = json_encode($dadoCarreg);
        echo 'GRAVOU-CARREG_' . $retCarreg;
        
    }
    
    private function salvarLog($dados, $pagina) {
        $logDAO = new LogDAO();
        $logDAO->salvarDados($dados, $pagina, $this->base);
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
    
    public function retCarregProd($versao, $info) {

        $versao = str_replace("_", ".", $versao);

        $carregDAO = new CarregDAO();

        if ($versao >= 2.00) {

            $retorno = array("dados" => $carregDAO->retCarregProd($info['dado'], $this->base));
            $carregDAO->updCarregProd($info['dado'], $this->base);
            $ret = json_encode($retorno);
            return $ret;
        }
    }
    
    public function retCarregComp($versao, $info) {

        $versao = str_replace("_", ".", $versao);

        $carregDAO = new CarregDAO();

        if ($versao >= 2.00) {
            $retorno = array("dados" => $carregDAO->retCarregComp($info['dado'], $this->base));
            $ret = json_encode($retorno);
            return $ret;
        }
    }
   
}
