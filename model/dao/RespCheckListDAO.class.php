<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once './dbutil/Conn.class.php';

/**
 * Description of ItemCheckList
 *
 * @author anderson
 */
class RespCheckListDAO extends Conn {

    ///////////////////////////////////////////////VERSAO 2/////////////////////////////////////////////////////////////
    //    DADOS QUE VEM DA PAGINA INSERIRCHECKLIST2

    public function verifRespCheckList($idCab, $i) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " ITEM_BOLETIM_CHECK "
                . " WHERE "
                . " ID_BOLETIM = " . $idCab
                . " AND "
                . " ITMANPREV_ID = " . $i->idItBDIt;

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

    public function insRespCheckList($idCab, $i) {

        $grupo = '';
        $questao = '';

        $select = " SELECT "
                . " VIPC.ITMANPREV_ID AS ID, "
                . " CARACTER(VIPC.PROC_OPER) AS QUESTAO, "
                . " CARACTER(VCC.DESCR) AS GRUPO "
                . " FROM "
                . " V_ITEM_PLANO_CHECK VIPC "
                . " , V_COMPONENTE_CHECK VCC "
                . " WHERE "
                . " VIPC.ITMANPREV_ID = " . $i->idItBDIt . " "
                . " AND "
                . " VIPC.COMPONENTE_ID = VCC.COMPONENTE_ID ";

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $inf) {
            $questao = $inf['QUESTAO'];
            $grupo = $inf['GRUPO'];
        }

        if (!isset($i->opIt) || empty($i->opIt)) {
            $i->opIt = 0;
        }

        $sql = " INSERT INTO ITEM_BOLETIM_CHECK ( "
                . " ID_BOLETIM "
                . " , GRUPO "
                . " , QUESTAO "
                . " , RESP_CD "
                . " , ITMANPREV_ID "
                . " ) "
                . " VALUES ( "
                . " " . $idCab . " "
                . " , '" . $grupo . "' "
                . " , '" . $questao . "' "
                . " , " . $i->opIt . " "
                . " , " . $i->idItBDIt . ")";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    ///////////////////////////////////////////////VERSAO 1 COM DATA DE CELULAR//////////////////////////////////////////////////////////
    //    DADOS QUE VEM DA PAGINA APONTACHECKLISTDT

    public function verifRespCheckListCDC($idCab, $i) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " ITEM_BOLETIM_CHECK "
                . " WHERE "
                . " ID_BOLETIM = " . $idCab
                . " AND "
                . " ITMANPREV_ID = " . $i->idItBDIt . " ";

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

    public function insRespCheckListCDC($idCab, $i) {

        $grupo = '';
        $questao = '';

        $select = " SELECT "
                . " VIPC.ITMANPREV_ID AS ID, "
                . " CARACTER(VIPC.PROC_OPER) AS QUESTAO, "
                . " CARACTER(VCC.DESCR) AS GRUPO "
                . " FROM "
                . " V_ITEM_PLANO_CHECK VIPC "
                . " , V_COMPONENTE_CHECK VCC "
                . " WHERE "
                . " VIPC.ITMANPREV_ID = " . $i->idItBDIt . " "
                . " AND "
                . " VIPC.COMPONENTE_ID = VCC.COMPONENTE_ID ";

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $inf) {
            $questao = $inf['QUESTAO'];
            $grupo = $inf['GRUPO'];
        }

        if (!isset($i->opIt) || empty($i->opIt)) {
            $i->opIt = 0;
        }

        $sql = " INSERT INTO ITEM_BOLETIM_CHECK ( "
                . " ID_BOLETIM "
                . " , GRUPO "
                . " , QUESTAO "
                . " , RESP_CD "
                . " , ITMANPREV_ID "
                . " ) "
                . " VALUES ( "
                . " " . $idCab . " "
                . " , '" . $grupo . "' "
                . " , '" . $questao . "' "
                . " , " . $i->opIt . " "
                . " , " . $i->idItBDIt . ")";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    ///////////////////////////////////////////////SEM DATA DE CELULAR//////////////////////////////////////////////////////////
    //    DADOS QUE VEM DA PAGINA APONTACHECKLIST

    public function verifRespCheckListSDC($idCab, $i) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " ITEM_BOLETIM_CHECK "
                . " WHERE "
                . " ID_BOLETIM = " . $idCab
                . " AND "
                . " ITMANPREV_ID = " . $i->idItItemCheckList . " ";

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

    public function insRespCheckListSDC($idCab, $i) {

        $grupo = '';
        $questao = '';

        $select = " select "
                . " vipc.itmanprev_id as id, "
                . " CARACTER(vipc.proc_oper) as questao, "
                . " CARACTER(vcc.descr) as grupo "
                . " from "
                . " v_item_plano_check vipc "
                . " , v_componente_check vcc "
                . " where "
                . " vipc.itmanprev_id = " . $i->idItItemCheckList . " "
                . " and "
                . " vipc.componente_id = vcc.componente_id ";

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $inf) {
            $questao = $inf['QUESTAO'];
            $grupo = $inf['GRUPO'];
        }

        if (!isset($i->opcaoItemCheckList) || empty($i->opcaoItemCheckList)) {
            $i->opcaoItemCheckList = 0;
        }
        $sql = " INSERT INTO ITEM_BOLETIM_CHECK ( "
                . " ID_BOLETIM "
                . " , GRUPO "
                . " , QUESTAO "
                . " , RESP_CD "
                . " , ITMANPREV_ID "
                . " ) "
                . " VALUES ( "
                . " " . $idCab
                . " , '" . $grupo . "' "
                . " , '" . $questao . "' "
                . " , " . $i->opcaoItemCheckList . " "
                . " , " . $i->idItItemCheckList . ")";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

}
