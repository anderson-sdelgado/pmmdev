<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once './dbutil/Conn.class.php';
require_once './model/dao/AjusteDataHoraDAO.class.php';

/**
 * Description of ApontFertDAO
 *
 * @author anderson
 */
class ApontFertDAO extends Conn {

    //put your code here

    public function verifApontFert($idBol, $apont) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PMM_APONTAMENTO_FERT "
                . " WHERE "
                . " DTHR_CEL = TO_DATE('" . $apont->dthApontaFert . "','DD/MM/YYYY HH24:MI')"
                . " AND "
                . " BOLETIM_ID = " . $idBol . " ";

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

    public function idApontFert($idBol, $apont) {

        $select = " SELECT "
                . " ID AS ID "
                . " FROM "
                . " PMM_APONTAMENTO_FERT "
                . " WHERE "
                . " DTHR_CEL = TO_DATE('" . $apont->dthrAponta . "','DD/MM/YYYY HH24:MI')"
                . " AND "
                . " BOLETIM_ID = " . $idBol . " ";

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

    public function insApontFert($idBol, $apont) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        $raio = 0;

        if ($apont->paradaApontaFert == 0) {
            $apont->paradaApontaFert = "null";
        } else {
            $raio = 45;
        }

        if ($apont->bocalApontaFert == 0) {
            $apont->bocalApontaFert = "null";
        }

        if ($apont->pressaoApontaFert == 0) {
            $apont->pressaoApontaFert = "null";
        }

        if ($apont->velocApontaFert == 0) {
            $apont->velocApontaFert = "null";
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
                . " , LATITUDE "
                . " , LONGITUDE "
                . " , STATUS_CONEXAO "
                . " ) "
                . " VALUES ("
                . " " . $idBol
                . " , " . $apont->osApontaFert
                . " , " . $apont->ativApontaFert
                . " , " . $apont->paradaApontaFert
                . " , " . $ajusteDataHoraDAO->dataHoraGMT($apont->dthrApontaFert)
                . " , TO_DATE('" . $apont->dthrApontaFert . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " , " . $apont->bocalApontaFert
                . " , " . $apont->pressaoApontaFert
                . " , " . $apont->velocApontaFert
                . " , " . $raio
                . " , " . $apont->latitudeApontaFert
                . " , " . $apont->longitudeApontaFert
                . " , " . $apont->statusConApontaFert
                . " )";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

}
