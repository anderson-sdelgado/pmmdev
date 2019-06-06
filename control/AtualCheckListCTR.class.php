<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require('./model/dao/EquipDAO.class.php');
require('./model/dao/ItemCheckListDAO.class.php');

/**
 * Description of AtualCheckList
 *
 * @author anderson
 */
class AtualCheckListCTR {

    //put your code here

    public function dados($info) {

        $equipDAO = new EquipDAO();
        $itemCheckListDAO = new ItemCheckListDAO();

        $dado = $info['dado'];

        $dadosEquip = array("dados" => $equipDAO->dados($dado));
        $resEquip = json_encode($dadosEquip);

        $dadosItemCheckList = array("dados" => $itemCheckListDAO->dados());
        $resItemCheckList = json_encode($dadosItemCheckList);

        return $resEquip . "_" . $resItemCheckList;
    }

}
