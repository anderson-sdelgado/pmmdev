<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
/**
 * Description of ApontMMFertDAO
 *
 * @author anderson
 */
class ApontMMFertDAO extends Conn {

    public function verifApontMM($idBol, $apont, $base) {

        $select = " SELECT "
                    . " COUNT(*) AS QTDE "
                . " FROM "
                    . " PMM_APONTAMENTO "
                . " WHERE "
                    . " ID_CEL = " . $apont->idApontMMFert
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

    public function idApontMM($idBol, $apont, $base) {

        $select = " SELECT "
                    . " ID AS ID "
                . " FROM "
                    . " PMM_APONTAMENTO "
                . " WHERE "
                    . " ID_CEL = " . $apont->idApontMMFert
                . " AND "
                    . " BOLETIM_ID = " . $idBol;

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

    public function insApontMM($idBol, $apont, $base) {

        if ($apont->transbApontMMFert == 0) {
            $apont->transbApontMMFert = "null";
        }

        if ($apont->paradaApontMMFert == 0) {
            $apont->paradaApontMMFert = "null";
        }
        
        if ($apont->osApontMMFert == 0) {
            $apont->osApontMMFert = "null";
        }
        
        if ($apont->ativApontMMFert == 0) {
            $apont->ativApontMMFert = "null";
        }
        
        if ($apont->idFrenteApontMMFert == 0) {
            $apont->idFrenteApontMMFert = "null";
        }
        
        if ($apont->idProprApontMMFert == 0) {
            $apont->idProprApontMMFert = "null";
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
                . " , ID_CEL "
                . " , FRENTE_ID "
                . " , PROPRAGR_ID "
                . " ) "
                . " VALUES ("
                . " " . $idBol
                . " , " . $apont->osApontMMFert
                . " , " . $apont->ativApontMMFert
                . " , " . $apont->paradaApontMMFert
                . " , TO_DATE('" . $apont->dthrApontMMFert . "','DD/MM/YYYY HH24:MI')"
                . " , TO_DATE('" . $apont->dthrApontMMFert . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " , " . $apont->transbApontMMFert
                . " , " . $apont->longitudeApontMMFert
                . " , " . $apont->latitudeApontMMFert
                . " , " . $apont->statusConApontMMFert
                . " , " . $apont->idApontMMFert
                . " , " . $apont->idFrenteApontMMFert
                . " , " . $apont->idProprApontMMFert
                . " )";

        $this->Conn = parent::getConn($base);
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();

    }
    
    public function updateApontMMOSAtiv($idBol, $apont, $base) {

        $sql = "UPDATE PMM_APONTAMENTO "
                . " SET "
                . " OS_NRO = " . $apont->osApontMMFert
                . " , ATIVAGR_ID = " . $apont->ativApontMMFert
                . " WHERE "
                . " BOLETIM_ID = " . $idBol
                . " AND "
                . " OS_NRO IS NULL";

        $this->Conn = parent::getConn($base);
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    public function verifQtdeApontMM($idBol, $base) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PMM_APONTAMENTO "
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
    
    public function verifApontFert($idBol, $apont, $base) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PMM_APONTAMENTO_FERT "
                . " WHERE "
                . " ID_CEL = " . $apont->idApontMMFert
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
                . " ID_CEL = " . $apont->idApontMMFert
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

        $raio = "null";

        if ($apont->paradaApontMMFert == 0) {
            $apont->paradaApontMMFert = "null";
            $raio = 45;
        }

        if ($apont->bocalApontMMFert == 0) {
            $apont->bocalApontMMFert = "null";
        }

        if ($apont->pressaoApontMMFert == 0) {
            $apont->pressaoApontMMFert = "null";
        }

        if ($apont->velocApontMMFert == 0) {
            $apont->velocApontMMFert = "null";
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
                . " , " . $apont->osApontMMFert
                . " , " . $apont->ativApontMMFert
                . " , " . $apont->paradaApontMMFert
                . " , TO_DATE('" . $apont->dthrApontMMFert . "','DD/MM/YYYY HH24:MI')"
                . " , TO_DATE('" . $apont->dthrApontMMFert . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " , " . $apont->bocalApontMMFert
                . " , " . $apont->pressaoApontMMFert
                . " , " . $apont->velocApontMMFert
                . " , " . $raio
                . " , " . $apont->longitudeApontMMFert
                . " , " . $apont->latitudeApontMMFert
                . " , " . $apont->statusConApontMMFert
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
