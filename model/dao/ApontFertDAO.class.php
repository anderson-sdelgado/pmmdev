<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
require_once('../model/dao/AjusteDataHoraDAO.class.php');

/**
 * Description of ApontFertDAO
 *
 * @author anderson
 */
class ApontFertDAO extends Conn {

    //put your code here

    public function verifApontFert($idBol, $apont, $base) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PMM_APONTAMENTO_FERT "
                . " WHERE "
                . " DTHR_CEL = TO_DATE('" . $apont->dthrApontFert . "','DD/MM/YYYY HH24:MI')"
                . " AND "
                . " BOLETIM_ID = " . $idBol . " ";

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

    public function idApontFert($idBol, $apont, $base) {

        $select = " SELECT "
                . " ID AS ID "
                . " FROM "
                . " PMM_APONTAMENTO_FERT "
                . " WHERE "
                . " DTHR_CEL = TO_DATE('" . $apont->dthrApontFert . "','DD/MM/YYYY HH24:MI')"
                . " AND "
                . " BOLETIM_ID = " . $idBol . " ";

        $this->Conn = parent::getConn($base);
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $id = $item['ID'];
        }

        return $id;
    }

    public function insApontFert($idBol, $apont, $base) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        $raio = "null";

        if ($apont->paradaApontFert == 0) {
            $apont->paradaApontFert = "null";
            $raio = 45;
        }

        if ($apont->bocalApontFert == 0) {
            $apont->bocalApontFert = "null";
        }

        if ($apont->pressaoApontFert == 0) {
            $apont->pressaoApontFert = "null";
        }

        if ($apont->velocApontFert == 0) {
            $apont->velocApontFert = "null";
        }

        $sql = "INSERT INTO PMM_APONTAMENTO_FERT ("
                . " BOLETIM_ID "
                . " , OS_NRO "
                . " , ATIVAGR_ID "
                . " , MOTPARADA_ID "
                . " , DTHR "
                . " , DTHR_CEL "
                . " , DTHR_TRANS "
                . " , BOCALBOMBA_ID "
                . " , PRESSAO "
                . " , VELOCIDADE "
                . " , RAIO "
                . " , LONGITUDE "
                . " , LATITUDE "
                . " , STATUS_CONEXAO "
                . " ) "
                . " VALUES ("
                . " " . $idBol
                . " , " . $apont->osApontFert
                . " , " . $apont->ativApontFert
                . " , " . $apont->paradaApontFert
                . " , " . $ajusteDataHoraDAO->dataHoraGMT($apont->dthrApontFert, $base)
                . " , TO_DATE('" . $apont->dthrApontFert . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " , " . $apont->bocalApontFert
                . " , " . $apont->pressaoApontFert
                . " , " . $apont->velocApontFert
                . " , " . $raio
                . " , " . $apont->longitudeApontFert
                . " , " . $apont->latitudeApontFert
                . " , " . $apont->statusConApontFert
                . " )";

        $this->Conn = parent::getConn($base);
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
        
    }

    public function verifQtdeApontFert($idBol, $base) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PMM_APONTAMENTO_FERT "
                . " WHERE "
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
    
}
