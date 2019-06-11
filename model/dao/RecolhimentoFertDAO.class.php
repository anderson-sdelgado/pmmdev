<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once './dbutil/Conn.class.php';
require_once 'AjusteDataHoraDAO.class.php';

/**
 * Description of RecolhimentoFertDAO
 *
 * @author anderson
 */
class RecolhimentoFertDAO extends Conn {

    //put your code here
    public function verifRecolhimentoFert($idBol, $recol) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PMM_RECOLHIMENTO_FERT "
                . " WHERE "
                . " OS_NRO = " . $recol->nroOSRecol
                . " AND "
                . " DTHR_CEL = TO_DATE('" . $recol->dthrRecol . "','DD/MM/YYYY HH24:MI') "
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

    public function insRecolhimentoFert($idBol, $recol) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        $sql = "INSERT INTO PMM_RECOLHIMENTO_FERT ("
                . " BOLETIM_ID "
                . " , OS_NRO "
                . " , MANGUEIRA_REC "
                . " , DTHR "
                . " , DTHR_CEL "
                . " , DTHR_TRANS "
                . " ) "
                . " VALUES ("
                . " " . $idBol
                . " , " . $recol->nroOSRecol
                . " , " . $recol->valorRecol
                . " , " . $ajusteDataHoraDAO->dataHoraGMT($recol->dthrRecol)
                . " , TO_DATE('" . $recol->dthrRecol . "','DD/MM/YYYY HH24:MI') "
                . " , SYSDATE "
                . " )";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

}
