<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once './dbutil/Conn.class.php';
require_once 'AjusteDataHoraDAO.class.php';

/**
 * Description of RendimentoMM
 *
 * @author anderson
 */
class RendimentoMMDAO extends Conn {

    //put your code here
    public function verifRendimentoMM($idBol, $rend) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PMM_RENDIMENTO "
                . " WHERE "
                . " OS_NRO = " . $rend->nroOSRendimento
                . " AND "
                . " DTHR_CEL = TO_DATE('" . $rend->dthrRendimento . "','DD/MM/YYYY HH24:MI') "
                . " AND "
                . " BOLETIM_ID = " . $idBol;

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

    public function insRendimentoMM($idBol, $rend) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

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
                . " , " . $rend->nroOSRendimento
                . " , " . $rend->valorRendimento
                . " , " . $ajusteDataHoraDAO->dataHoraIdBoletim($idBol, $rend->dthrRendimento)
                . " , TO_DATE('" . $rend->dthrRendimento . "','DD/MM/YYYY HH24:MI') "
                . " , SYSDATE "
                . " )";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

}
