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
        
            $jsonObjCarreg  = json_decode($dados);
            $dadosCarreg = $jsonObjCarreg->carreg;
            $this->salvarDadosCarregInsumo($dadosCarreg);
        
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
    
    private function salvarDadosCarregInsumo($dadosCarreg) {
        $carregDAO = new CarregDAO();
        $idCarregArray = array();
        foreach ($dadosCarreg as $carreg) {
            $v = $carregDAO->verifCarregProd($carreg, $this->base);
            if ($v == 0) {
                $carregDAO->cancelCarregProd($carreg, $this->base);
                $carregDAO->insCarregProd($carreg, $this->base);
            }
            $idCarregArray[] = array("idCarreg" => $carreg->idCarreg);
        }
        $dadoCarreg = array("dados"=>$idCarregArray);
        $retCarreg = json_encode($dadoCarreg);

        echo 'GRAVOU-CARREGINSUMO_' . $retCarreg;
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
