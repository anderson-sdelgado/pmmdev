<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');

/**
 * Description of ImplementoDAO
 *
 * @author anderson
 */
class ImplementoMMDAO extends Conn {

    public function verifImplementoMM($idApont, $imp, $base) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PMM_IMPLEMENTO "
                . " WHERE "
                . " APONTAMENTO_ID = " . $idApont
                . " AND "
                . " NRO_EQUIP = " . $imp->codEquipImpleMM
                . " AND "
                . " DTHR_CEL = TO_DATE('" . $imp->dthrImpleMM . "','DD/MM/YYYY HH24:MI') ";

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

    public function insImplementoMM($idApont, $imp, $base) {

        $sql = "INSERT INTO PMM_IMPLEMENTO ("
                . " APONTAMENTO_ID "
                . " , NRO_EQUIP "
                . " , POS_EQUIP "
                . " , DTHR "
                . " , DTHR_CEL "
                . " , DTHR_TRANS "
                . " ) "
                . " VALUES ("
                . " " . $idApont
                . " , " . $imp->codEquipImpleMM
                . " , " . $imp->posImpleMM
                . " , TO_DATE('" . $imp->dthrImpleMM . "','DD/MM/YYYY HH24:MI')"
                . " , TO_DATE('" . $imp->dthrImpleMM . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " )";

        $this->Conn = parent::getConn($base);
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

}
