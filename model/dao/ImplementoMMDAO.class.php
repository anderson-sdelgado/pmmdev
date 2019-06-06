<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once './dbutil/Conn.class.php';
require_once 'AjusteDataHoraDAO.class.php';

/**
 * Description of ImplementoDAO
 *
 * @author anderson
 */
class ImplementoMMDAO {

    //put your code here
    public function verifImplementoMM($idApont, $imp) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PMM_IMPLEMENTO "
                . " WHERE "
                . " APONTAMENTO_ID = " . $idApont
                . " AND "
                . " NRO_EQUIP = " . $imp->codEquipImplemento
                . " AND "
                . " POS_EQUIP = " . $imp->posImplemento
                . " AND "
                . " DTHR_CEL = TO_DATE('" . $imp->dthrImplemento . "','DD/MM/YYYY HH24:MI') ";

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

    public function insImplementoMM($idApont, $imp) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

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
                . " , " . $imp->codEquipImplemento
                . " , " . $imp->posImplemento
                . " , " . $ajusteDataHoraDAO->dataHoraIdApont($idApont, $imp->dthrImplemento)
                . " , TO_DATE('" . $imp->dthrImplemento . "','DD/MM/YYYY HH24:MI') "
                . " , SYSDATE "
                . " )";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

}
