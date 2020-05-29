<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/dao/EquipSegDAO.class.php');
/**
 * Description of EquipSegCTR
 *
 * @author anderson
 */
class EquipSegCTR {

    //put your code here

    public function dados($versao) {
        
        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 2.00){

            $equipSegDAO = new EquipSegDAO();

            $dados = array("dados" => $equipSegDAO->dados());
            $json_str = json_encode($dados);

            return $json_str;
        
        }
        
    }
    
    public function dadosECM($versao) {
        
        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 2.00){

            $equipSegDAO = new EquipSegDAO();

            $dados = array("dados" => $equipSegDAO->dadosECM());
            $json_str = json_encode($dados);

            return $json_str;
        
        }
        
    }

}
