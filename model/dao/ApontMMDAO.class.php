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
                . " , " . $apont->statusConApontMM
                . " )";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();

        $sql = "UPDATE PMM_APONTAMENTO_LOGTRAC "
                . " SET DTAFIM = " . $ajusteDataHoraDAO->dataHoraGMT($apont->dthrApontMM)
                . " WHERE ID = "
                . " NVL(( "
                . " SELECT MAX(A1.ID) "
                . " FROM PMM_APONTAMENTO_LOGTRAC A1"
                . " , EQUIP E1"
                . " , PMM_BOLETIM B1 "
                . " WHERE B1.ID = " . $idBol
                . " AND A1.CDGEQUIPAMENTO = E1.NRO_EQUIP "
                . " AND E1.EQUIP_ID = B1.EQUIP_ID), 0)";

        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();

        if ($apont->paradaApontMM != "null") {

            $select = " SELECT "
                    . " COUNT(A.ID) AS QTDE "
                    . " FROM "
                    . " PMM_APONTAMENTO_LOGTRAC A "
                    . " WHERE "
                    . " A.ID = (SELECT "
                                    . " MAX(A1.ID) "
                                . " FROM "
                                    . " PMM_APONTAMENTO A1"
                                . " WHERE "
                                    . " A1.MOTPARADA_ID IS NOT NULL)";

            $this->Read = $this->Conn->prepare($select);
            $this->Read->setFetchMode(PDO::FETCH_ASSOC);
            $this->Read->execute();
            $res4 = $this->Read->fetchAll();

            foreach ($res4 as $item4) {
                $v = $item4['QTDE'];
            }

            if ($v == 0) {

                $sql = "INSERT INTO PMM_APONTAMENTO_LOGTRAC "
                        . " (ID, CDGEQUIPAMENTO, DTAINICIO, CDGMOTIVOPARADA, CDGOM, CDGFUNCIONARIO) "
                        . " SELECT "
                        . " A.ID "
                        . " , E.NRO_EQUIP AS CDGEQUIPAMENTO "
                        . " , A.DTHR AS DTAINICIO "
                        . " , P.CD AS CDGMOTIVOPARADA "
                        . " , A.OS_NRO AS CDGOM "
                        . ", B.FUNC_MATRIC AS CDGFUNCIONARIO "
                        . " FROM "
                        . " PMM_BOLETIM B "
                        . " , PMM_APONTAMENTO A "
                        . " , EQUIP E "
                        . " , MOTIVO_PARADA P "
                        . " WHERE "
                        . " B.ID = " . $idBol
                        . " AND "
                        . " B.ID = A.BOLETIM_ID "
                        . " AND "
                        . " B.EQUIP_ID = E.EQUIP_ID "
                        . " AND A.MOTPARADA_ID = P.MOTPARADA_ID "
                        . " AND A.ID = (SELECT "
                                            . " MAX(A1.ID) "
                                        . " FROM "
                                            . " PMM_APONTAMENTO A1 "
                                        . " WHERE "
                                            . " A1.MOTPARADA_ID IS NOT NULL)";

                $this->Create = $this->Conn->prepare($sql);
                $this->Create->execute();
            }
        }
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
