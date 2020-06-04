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
    //put your code here
    
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
                $carregDAO->cancelCarregProd($c);
                $v = $carregDAO->verifCarregProd($c);
                if ($v == 0) {
                    $carregDAO->insCarregProd($c);
                }
            } elseif ($c->tipoCarreg == 2) {
                $carregDAO->cancelCarregComp($c);
                $v = $carregDAO->verifCarregComp($c);
                if ($v == 0) {
                    $carregDAO->insCarregComp($c);
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
        $logDAO->salvarDados($dados, $pagina);
        
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

            $retorno = array("dados" => $carregDAO->retLeiraComp($equip, $os));
            $ret = json_encode($retorno);
            return $ret;
        
        }
        
    }
    
    public function retLeiraCarregProd($versao, $info) {

        $versao = str_replace("_", ".", $versao);

        $carregDAO = new CarregDAO();

        if ($versao >= 2.00) {

            $jsonObj = json_decode($info['dado']);
            $dados = $jsonObj->dados;

            foreach ($dados as $d) {
                $equip = $d->equipCarreg;
            }
            
            $retorno = array("dados" => $carregDAO->retLeiraCarregProd($equip));
            $carregDAO->updCarregProd($equip);
            $ret = json_encode($retorno);
            return $ret;
        }
    }
    
    public function retCarregComp($versao, $info) {

        $versao = str_replace("_", ".", $versao);

        $carregDAO = new CarregDAO();

        if ($versao >= 2.00) {

            $jsonObj = json_decode($info['dado']);
            $dados = $jsonObj->dados;

            foreach ($dados as $d) {
                $equip = $d->equipCarreg;
            }
            
            $retorno = array("dados" => $carregDAO->retCarregComp($equip));
            $ret = json_encode($retorno);
            return $ret;
        }
    }
   
}
