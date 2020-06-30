<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
require_once('../model/dao/AjusteDataHoraDAO.class.php');

/**
 * Description of ApontamentoMM
 *
 * @author anderson
 */
class ApontMMDAO extends Conn {

    public function verifApontMM($idBol, $apont) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PMM_APONTAMENTO "
                . " WHERE "
                . " DTHR_CEL = TO_DATE('" . $apont->dthrApontMM . "','DD/MM/YYYY HH24:MI')"
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

    public function idApontMM($idBol, $apont) {

        $select = " SELECT "
                . " ID AS ID "
                . " FROM "
                . " PMM_APONTAMENTO "
                . " WHERE "
                . " DTHR_CEL = TO_DATE('" . $apont->dthrApontMM . "','DD/MM/YYYY HH24:MI')"
                . " AND "
                . " BOLETIM_ID = " . $idBol;

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $id = $item['ID'];
        }

        return $id;
    }

    public function insApontMM($idBol, $apont) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        if ($apont->transbApontMM == 0) {
            $apont->transbApontMM = "null";
        }

        if ($apont->paradaApontMM == 0) {
            $apont->paradaApontMM = "null";
        }

        $sql = "INSERT INTO PMM_APONTAMENTO ("
                . " BOLETIM_ID "
                . " , OS_NRO "
                . " , ATIVAGR_ID "
                . " , MOTPARADA_ID "
                . " , DTHR "
                . " , DTHR_CEL "
                . " , DTHR_TRANS "
                . " , NRO_EQUIP_TRANSB "
                . " , LONGITUDE "
                . " , LATITUDE "
                . " , STATUS_CONEXAO "
                . " ) "
                . " VALUES ("
                . " " . $idBol
                . " , " . $apont->osApontMM
                . " , " . $apont->ativApontMM
                . " , " . $apont->paradaApontMM
                . " , " . $ajusteDataHoraDAO->dataHoraGMT($apont->dthrApontMM)
                . " , TO_DATE('" . $apont->dthrApontMM . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " , " . $apont->transbApontMM
                . " , " . $apont->longitudeApontMM
                . " , " . $apont->latitudeApontMM
                . " , " . $apont->statusConApontMM
                . " )";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();

    }

    public function verifQtdeApontMM($idBol) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PMM_APONTAMENTO "
                . " WHERE "
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
    
}
