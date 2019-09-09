<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/dao/EquipDAO.class.php');
require_once('../model/dao/REquipAtivDAO.class.php');
/**
 * Description of EquipCTR
 *
 * @author anderson
 */
class EquipCTR {
    //put your code here
    
    public function dados($versao, $info) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 2.00){
        
            $equipDAO = new EquipDAO();
            $rEquipAtivDAO = new REquipAtivDAO();

            $dado = $info['dado'];

            $dadosEquip = array("dados" => $equipDAO->dados($dado));
            $resEquip = json_encode($dadosEquip);

            $dadosREquipAtivDAO = array("dados" => $rEquipAtivDAO->dados($dado));
            $resREquipAtivDAO = json_encode($dadosREquipAtivDAO);

            return $resEquip . "#" . $resREquipAtivDAO;
        
        }
        
    }
    
}
