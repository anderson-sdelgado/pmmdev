<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
require_once('../model/dao/AjusteDataHoraDAO.class.php');
/**
 * Description of ItemPneuDAO
 *
 * @author anderson
 */
class ItemPneuDAO extends Conn {
    //put your code here
    
    public function verifItemPneu($idBolPneu, $itemPneu, $base) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PMP_ITEM_MED "
                . " WHERE "
                . " BOLETIM_ID = " . $idBolPneu
                . " AND "
                . " NRO_PNEU LIKE '" . $itemPneu->nroPneuItemPneu . "'"
                . " AND "
                . " DTHR_CEL = TO_DATE('" . $itemPneu->dthrItemPneu . "','DD/MM/YYYY HH24:MI')";

        $this->Conn = parent::getConn($base);
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();
        foreach ($result as $item) {
            $v = $item['QTDE'];
        }
        return $v;
    }

    public function insItemPneu($idBolPneu, $itemPneu, $base) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();
        
        $sql = "INSERT INTO PMP_ITEM_MED ("
                . " BOLETIM_ID "
                . " , POSPNCONF_ID "
                . " , NRO_PNEU "
                . " , PRESSAO_ENC "
                . " , PRESSAO_COL "
                . " , DTHR "
                . " , DTHR_CEL "
                . " , DTHR_TRANS "
                . " ) "
                . " VALUES ("
                . " " . $idBolPneu
                . " , " . $itemPneu->posItemPneu
                . " , '" . $itemPneu->nroPneuItemPneu . "'"
                . " , " . $itemPneu->pressaoEncItemPneu
                . " , " . $itemPneu->pressaoColItemPneu
                . " , " . $ajusteDataHoraDAO->dataHoraGMT($itemPneu->dthrItemPneu, $base)
                . " , TO_DATE('" . $itemPneu->dthrItemPneu . "','DD/MM/YYYY HH24:MI') "
                . " , SYSDATE "
                . " )";
        
        $this->Conn = parent::getConn($base);
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }
    
}
