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
            $carreg = $jsonObj->dados;
            $this->salvarDadosCarreg($carreg);
        
        }
        
    }
    
    private function salvarDadosCarreg($carreg) {
        
        $carregDAO = new CarregDAO();
        foreach ($carreg as $c) {
            if ($c->tipoApontCarreg == 1) {
                $carregDAO->cancelCarregProd($carreg);
                $v = $carregDAO->verifCarregProd($c);
                if ($v == 0) {
                    $$carregDAO->insCarregProd($c);
                }
            } elseif ($c->tipoApontCarreg == 2) {
                $carregDAO->cancelCarregComp($carreg);
                $v = $carregDAO->verifCarregComp($c);
                if ($v == 0) {
                    $$carregDAO->insCarregComp($c);
                }
            }
        }
        echo 'GRAVOU-CARREG';
        
    }
    
    private function salvarLog($dados, $pagina) {
        
        $logDAO = new LogDAO();
        $logDAO->salvarDados($dados, $pagina);
        
    }
   
}
