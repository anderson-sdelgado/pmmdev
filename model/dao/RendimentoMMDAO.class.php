<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
/**
 * Description of RendimentoMM
 *
 * @author anderson
 */
class RendimentoMMDAO extends Conn {

    public function verifRendimentoMM($idBol, $rend, $base) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PMM_RENDIMENTO "
                . " WHERE "
                . " OS_NRO = " . $rend->nroOSRendMM
                . " AND "
                . " DTHR_CEL = TO_DATE('" . $rend->dthrRendMM . "','DD/MM/YYYY HH24:MI') "
                . " AND "
                . " BOLETIM_ID = " . $idBol;

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

    public function insRendimentoMM($idBol, $rend, $base) {

        $sql = "INSERT INTO PMM_RENDIMENTO ("
                . " BOLETIM_ID "
                . " , OS_NRO "
                . " , VL "
                . " , DTHR "
                . " , DTHR_CEL "
                . " , DTHR_TRANS "
                . " ) "
                . " VALUES ("
                . " " . $idBol
                . " , " . $rend->nroOSRendMM
                . " , " . $rend->valorRendMM
                . " , TO_DATE('" . $rend->dthrRendMM . "','DD/MM/YYYY HH24:MI') "
                . " , TO_DATE('" . $rend->dthrRendMM . "','DD/MM/YYYY HH24:MI') "
                . " , SYSDATE "
                . " )";

        $this->Conn = parent::getConn($base);
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

}
