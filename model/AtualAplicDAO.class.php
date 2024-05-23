<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');

/**
 * Description of AtualizaAplicDAO
 *
 * @author anderson
 */
class AtualAplicDAO extends Conn {
    //put your code here

    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function verAtual($idEquip) {

        $select = "SELECT "
                    . " COUNT(*) AS QTDE "
                . " FROM "
                    . " PMM_ATUAL "
                . " WHERE "
                    . " EQUIP_ID = " . $idEquip;

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
    
    public function verToken($token) {

        $select = "SELECT "
                    . " COUNT(*) AS QTDE "
                . " FROM "
                    . " PMM_ATUAL "
                . " WHERE "
                    . " TOKEN = '" . $token . "'";

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

    public function insAtual($idEquip, $versao, $aplic) {

        $sql = "INSERT INTO PMM_ATUAL ("
                            . " EQUIP_ID "
                            . " , VERSAO "
                            . " , APLIC "
                            . " , DTHR_ULT_ACESSO "
                            . " , TOKEN "
                        . " ) "
                        . " VALUES ("
                            . " " . $idEquip
                            . " , '" . $versao . "'"
                            . " , '" . $aplic . "'"
                            . " , SYSDATE "
                            . " , '" . strtoupper(md5($aplic . '-VERSAO_' . $versao . '-' . $idEquip)) . "'"
                        . " )";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    public function retAtual($idEquip) {

        $select = " SELECT "
                    . " VERSAO "
                . " FROM "
                    . " PMM_ATUAL "
                . " WHERE "
                    . " EQUIP_ID = " . $idEquip;

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }

    public function updAtual($idEquip, $versao, $aplic) {

        $sql = "UPDATE PMM_ATUAL "
                    . " SET "
                    . " VERSAO = '" . $versao . "'"
                    . " , APLIC = '" . $aplic . "'"
                    . " , DTHR_ULT_ACESSO = SYSDATE "
                    . " , TOKEN = '" . strtoupper(md5($aplic . '-VERSAO_' . $versao . '-' . $idEquip)) . "'"
                . " WHERE "
                    . " EQUIP_ID = " . $idEquip;

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    public function updUltAcesso($idEquip) {

        $sql = "UPDATE PMM_ATUAL "
                . " SET "
                    . " DTHR_ULT_ACESSO = SYSDATE "
                . " WHERE "
                    . " EQUIP_ID = " . $idEquip;

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }
    
    public function verAtualCheckList($idEquip) {

        $select = " SELECT "
                    . " PA.VERSAO "
                    . ", CASE "
                    . " WHEN NVL(ACM.EQUIP_NRO, 0) = 0 "
                    . " THEN 0 "
                    . " ELSE 1 "
                    . " END AS VERIF_CHECKLIST "
                . " FROM "
                    . " PMM_ATUAL PA "
                    . " , EQUIP E "
                    . " , (SELECT "
                            . " EQUIP_NRO "
                        . " FROM "
                            . " ATUALIZA_CHECKLIST_MOBILE "
                        . " WHERE "
                            . " DT_MOBILE IS NULL) ACM "
                . " WHERE "
                    . " PA.EQUIP_ID = " . $idEquip
                    . " AND "
                    . " E.EQUIP_ID = PA.EQUIP_ID "
                    . " AND "
                    . " E.NRO_EQUIP = ACM.EQUIP_NRO(+) ";

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }

    public function idCheckList($idEquip) {

        $select = " SELECT "
                    . " NVL(C.PLMANPREV_ID, 0) AS IDCHECKLIST "
                . " FROM "
                    . " USINAS.V_SIMOVA_EQUIP E "
                    . " , USINAS.V_EQUIP_PLANO_CHECK C "
                . " WHERE  "
                    . " E.EQUIP_ID = " . $idEquip
                    . " AND "
                    . " E.NRO_EQUIP = C.EQUIP_NRO(+) ";

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $cla = $item['IDCHECKLIST'];
        }

        return $cla;
    }

    public function dataHora() {

        $select = " SELECT "
                        . " TO_CHAR(SYSDATE, 'DD/MM/YYYY HH24:MI') AS DTHR "
                    . " FROM "
                        . " DUAL ";

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result1 = $this->Read->fetchAll();

        foreach ($result1 as $item) {
            $dthr = $item['DTHR'];
        }

        return $dthr;
    }
    
}
