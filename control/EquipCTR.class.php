<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require('./model/dao/EquipDAO.class.php');
require('./model/dao/REquipAtivDAO.class.php');
require('./model/dao/RAtivParadaDAO.class.php');
require('./model/dao/REquipPneuDAO.class.php');
/**
 * Description of EquipCTR
 *
 * @author anderson
 */
class EquipCTR {
    //put your code here
    
    public function dados($info) {

        $equipDAO = new EquipDAO();
        $rEquipAtivDAO = new REquipAtivDAO();
        $rAtivParadaDAO = new RAtivParadaDAO();
        $rEquipPneuDAO = new REquipPneuDAO();

        $dado = $info['dado'];

        $dadosEquip = array("dados" => $equipDAO->dados($dado));
        $resEquip = json_encode($dadosEquip);

        $dadosREquipAtivDAO = array("dados" => $rEquipAtivDAO->dados($dado));
        $resREquipAtivDAO = json_encode($dadosREquipAtivDAO);

        $dadosRAtivParadaDAO = array("dados" => $rAtivParadaDAO->dados($dado));
        $resRAtivParadaDAO = json_encode($dadosRAtivParadaDAO);

        $dadosREquipPneu = array("dados" => $rEquipPneuDAO->dados($dado));
        $resREquipPneu = json_encode($dadosREquipPneu);

        return $resEquip . "#" . $resREquipAtivDAO . "|" . $resRAtivParadaDAO . "_" . $resREquipPneu;
        
    }
    
}
