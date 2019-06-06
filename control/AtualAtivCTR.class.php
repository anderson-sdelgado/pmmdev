<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require('./model/dao/EquipDAO.class.php');
require('./model/dao/REquipAtivDAO.class.php');
require('./model/dao/RAtivParadaDAO.class.php');
require('./model/dao/OSDAO.class.php');
require('./model/dao/ROSAtivDAO.class.php');
/**
 * Description of AtualAtivCTR
 *
 * @author anderson
 */
class AtualAtivCTR {

    //put your code here

    public function dados($info) {

        $equipDAO = new EquipDAO();
        $rEquipAtivDAO = new REquipAtivDAO();
        $rAtivParadaDAO = new RAtivParadaDAO();
        $osDAO = new OSDAO();
        $rOSAtivDAO = new ROSAtivDAODAO();

        $dados = $info['dado'];
        $pos1 = strpos($dados, "_") + 1;
        $os = substr($dados, 0, ($pos1 - 1));
        $equip = substr($dados, $pos1);

        $dadosEquip = array("dados" => $equipDAO->dados($equip));
        $resEquip = json_encode($dadosEquip);

        $dadosEquipAtiv = array("dados" => $rEquipAtivDAO->dados($equip));
        $resEquipAtiv = json_encode($dadosEquipAtiv);

        $dadosAtivParada = array("dados" => $rAtivParadaDAO->dados($equip));
        $resAtivParada = json_encode($dadosAtivParada);

        $dadosOS = array("dados" => $osDAO->dados($os));
        $resOS = json_encode($dadosOS);

        $dadosOSAtiv = array("dados" => $rOSAtivDAO->dados($os));
        $resOSAtiv = json_encode($dadosOSAtiv);

        return $resEquip . "_" . $resEquipAtiv . "|" . $resAtivParada . "#" . $resOS . "?" . $resOSAtiv;
        
    }

}
