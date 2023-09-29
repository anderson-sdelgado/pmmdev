<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once ('../dbutil/Conn.class.php');
/**
 * Description of ItemManutPneuDAO
 *
 * @author anderson
 */
class ItemManutPneuDAO extends Conn {

    //put your code here

    public function verifItemManutPneu($idBolPneu, $itemManutPneu) {

        $select = " SELECT "
                        . " COUNT(*) AS QTDE "
                    . " FROM "
                        . " PMP_ITEM_MANUT "
                    . " WHERE "
                        . " BOLETIM_ID = " . $idBolPneu
                        . " AND "
                        . " CEL_ID = " . $itemManutPneu->idItemManutPneu;

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $v = $item['QTDE'];
        }

        return $v;
    }

    public function insItemManutPneu($idBolPneu, $itemManutPneu) {

        $sql = "INSERT INTO PMP_ITEM_MANUT ("
                            . " BOLETIM_ID "
                            . " , POSPNCONF_ID "
                            . " , NRO_PNEU_RET "
                            . " , NRO_PNEU_COL "
                            . " , DTHR "
                            . " , DTHR_CEL "
                            . " , DTHR_TRANS "
                            . " , CEL_ID "
                            . " ) "
                        . " VALUES ("
                            . " " . $idBolPneu
                            . " , " . $itemManutPneu->idPosConfItemManutPneu
                            . " , '" . $itemManutPneu->nroPneuRetItemManutPneu . "'"
                            . " , '" . $itemManutPneu->nroPneuColItemManutPneu . "'"
                            . " , TO_DATE('" . $itemManutPneu->dthrItemManutPneu . "','DD/MM/YYYY HH24:MI') "
                            . " , TO_DATE('" . $itemManutPneu->dthrItemManutPneu . "','DD/MM/YYYY HH24:MI') "
                            . " , SYSDATE "
                            . " , " . $itemManutPneu->idItemManutPneu
                        . " )";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

}
