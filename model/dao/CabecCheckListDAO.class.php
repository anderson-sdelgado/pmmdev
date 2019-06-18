<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once ('./dbutil/Conn.class.php');
require_once ('./model/dao/AjusteDataHoraDAO.class.php');

/**
 * Description of CabecChecklist
 *
 * @author anderson
 */
class CabecCheckListDAO extends Conn {

    ///////////////////////////////////////////////VERSAO 2/////////////////////////////////////////////////////////////
    //    DADOS QUE VEM DA PAGINA INSERIRCHECKLIST2
    
    public function verifCabecCheckList($cab) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " BOLETIM_CHECK "
                . " WHERE "
                . " DTHR_CELULAR  = TO_DATE('" . $cab->dtCab . "','DD/MM/YYYY HH24:MI') "
                . " AND "
                . " EQUIP_NRO = " . $cab->equipCab;

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

    public function idCabecCheckList($cab) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        $select = " SELECT "
                . " ID_BOLETIM AS ID "
                . " FROM "
                . " BOLETIM_CHECK "
                . " WHERE "
                . " DTHR_CELULAR  = TO_DATE('" . $cab->dtCab . "','DD/MM/YYYY HH24:MI') "
                . " AND "
                . " EQUIP_NRO = " . $cab->equipCab;

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $id = $item['ID'];
        }

        return $id;
    }

    public function insCabecCheckList($cab) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        $select = " SELECT "
                . " NRO_TURNO "
                . " FROM "
                . " USINAS.TURNO_TRAB "
                . " WHERE TURNOTRAB_ID = " . $cab->turnoCab;

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $turno = $item['NRO_TURNO'];
        }

        $sql = " INSERT INTO BOLETIM_CHECK ( "
                . " EQUIP_NRO "
                . " , FUNC_CD "
                . " , DT "
                . " , DTHR_CELULAR "
                . " , NRO_TURNO "
                . " ) "
                . " VALUES ( "
                . " " . $cab->equipCab . " "
                . " , " . $cab->funcCab . ""
                . " , " . $ajusteDataHoraDAO->dataHoraGMT($cab->dtCab)
                . " , TO_DATE('" . $cab->dtCab . "','DD/MM/YYYY HH24:MI') "
                . " , " . $turno . ")";

        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();

        if ($cab->dtAtualCab != "0") {

            $sql = " UPDATE USINAS.ATUALIZA_CHECKLIST_MOBILE  "
                    . " SET DT_MOBILE = TO_DATE('" . $cab->dtAtualCab . "','DD/MM/YYYY HH24:MI') "
                    . " WHERE EQUIP_NRO = " . $cab->equipCab;

            $this->Create = $this->Conn->prepare($sql);
            $this->Create->execute();
        }
    }

    ///////////////////////////////////////////////VERSAO 1 COM DATA DE CELULAR//////////////////////////////////////////////////////////
    //    DADOS QUE VEM DA PAGINA APONTACHECKLISTDT
    
    public function verifCabecCheckListCDC($cab) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " BOLETIM_CHECK "
                . " WHERE "
                . " DT = TO_DATE('" . $cab->dtCab . "','DD/MM/YYYY HH24:MI') "
                . " AND "
                . " EQUIP_NRO = " . $cab->equipCab;

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

    public function idCabecCheckListCDC($cab) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        $select = " SELECT "
                . " ID_BOLETIM AS ID "
                . " FROM "
                . " BOLETIM_CHECK "
                . " WHERE "
                . " DT = TO_DATE('" . $cab->dtCab . "','DD/MM/YYYY HH24:MI') "
                . " AND "
                . " EQUIP_NRO = " . $cab->equipCab;

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $id = $item['ID'];
        }

        return $id;
    }

    public function insCabecCheckListCDC($cab) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        $select = " SELECT "
                . " NRO_TURNO "
                . " FROM "
                . " USINAS.V_SIMOVA_TURNO_EQUIP_NEW "
                . " WHERE TURNOTRAB_ID = " . $cab->turnoCab;

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $turno = $item['NRO_TURNO'];
        }

        $sql = " INSERT INTO BOLETIM_CHECK ( "
                . " EQUIP_NRO "
                . " , FUNC_CD "
                . " , DT "
                . " , NRO_TURNO "
                . " ) "
                . " VALUES ( "
                . " " . $cab->equipCab . " "
                . " , " . $cab->funcCab . ""
                . " , TO_DATE('" . $cab->dtCab . "','DD/MM/YYYY HH24:MI') "
                . " , " . $turno . ")";

        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();

    }
    
    ///////////////////////////////////////////////SEM DATA DE CELULAR//////////////////////////////////////////////////////////
    //    DADOS QUE VEM DA PAGINA APONTACHECKLIST
    
    public function verifCabecCheckListSDC($cab) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " BOLETIM_CHECK "
                . " WHERE "
                . " DT = TO_DATE('" . $cab->dtCabecCheckList . "','DD/MM/YYYY HH24:MI') "
                . " AND "
                . " EQUIP_NRO = " . $cab->equipCabecCheckList;

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

    public function idCabecCheckListSDC($cab) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        $select = " SELECT "
                . " ID_BOLETIM AS ID "
                . " FROM "
                . " BOLETIM_CHECK "
                . " WHERE "
                . " DT = TO_DATE('" . $cab->dtCabecCheckList . "','DD/MM/YYYY HH24:MI') "
                . " AND "
                . " EQUIP_NRO = " . $cab->equipCabecCheckList;

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $id = $item['ID'];
        }

        return $id;
    }

    public function insCabecCheckListSDC($cab) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        $select = " SELECT "
                . " NRO_TURNO "
                . " FROM "
                . " USINAS.V_SIMOVA_TURNO_EQUIP_NEW "
                . " WHERE TURNOTRAB_ID = " . $cab->turnoCabecCheckList;

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $turno = $item['NRO_TURNO'];
        }

        $sql = " INSERT INTO BOLETIM_CHECK ( "
                . " EQUIP_NRO "
                . " , FUNC_CD "
                . " , DT "
                . " , NRO_TURNO "
                . " ) "
                . " VALUES ( "
                . " " . $cab->equipCabecCheckList . " "
                . " , " . $cab->funcCabecCheckList . ""
                . " , TO_DATE('" . $cab->dtCabecCheckList . "','DD/MM/YYYY HH24:MI') "
                . " , " . $turno . ")";

        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();

    }
    
}
