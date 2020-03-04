<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/dao/TipoFrenteDAO.class.php');
require_once('../model/dao/PlantioDAO.class.php');
require_once('../model/dao/PerdaDAO.class.php');
/**
 * Description of InforProdCTR
 *
 * @author anderson
 */
class InformativoCTR {
    //put your code here
    
    public function dados($versao, $info) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 2.02){
        
            $dado = $info['dado'];

            $tipoFrenteDAO = new TipoFrenteDAO();
            $plantioDAO = new PlantioDAO();
            $perdaDAO = new PerdaDAO();
            
            $tipoFrente = $tipoFrenteDAO->dados($dado);
            
            if($tipoFrente == 1) {
                $dadosPlantio = array("dados" => $plantioDAO->dados($dado));
                $retorno = json_encode($dadosPlantio);
            }
            else if($tipoFrente == 3) {
                $dadosPerda = array("dados" => $perdaDAO->dados($dado));
                $retorno = json_encode($dadosPerda);
            }
            else{
                $retorno = "";
            }

            return "tipo=" . $tipoFrente . "_" . $retorno;
        
        }
        
    }
    
}
