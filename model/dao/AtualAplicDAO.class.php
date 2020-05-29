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

    public function verAtual($equip) {

        $select = "SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PMM_ATUALIZACAO "
                . " WHERE "
                . " EQUIP_ID = " . $equip;

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

    public function insAtual($equip, $va) {

        $sql = "INSERT INTO PMM_ATUALIZACAO ("
                . " EQUIP_ID "
                . " , VERSAO_ATUAL "
                . " , VERSAO_NOVA "
                . " , DTHR_ULT_ATUAL "
                . " ) "
                . " VALUES ("
                . " " . $equip
                . " , TRIM(TO_CHAR(" . $va . ", '99999999D99')) "
                . " , TRIM(TO_CHAR(" . $va . ", '99999999D99')) "
                . " , SYSDATE "
                . " )";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    public function retAtual($equip) {

        $select = " SELECT "
                . " VERSAO_NOVA"
                . " , VERSAO_ATUAL"
                . " FROM "
                . " PMM_ATUALIZACAO "
                . " WHERE "
                . " EQUIP_ID = " . $equip;

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }

    public function updAtualNova($equip, $va) {

        $sql = "UPDATE PMM_ATUALIZACAO "
                . " SET "
                . " VERSAO_ATUAL = TRIM(TO_CHAR(" . $va . ", '99999999D99'))"
                . " , VERSAO_NOVA = TRIM(TO_CHAR(" . $va . ", '99999999D99'))"
                . " , DTHR_ULT_ATUAL = SYSDATE "
                . " WHERE "
                . " EQUIP_ID = " . $equip;

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    public function verAtualCheckList($equip) {

        $select = " SELECT "
                . " PA.VERSAO_ATUAL"
                . ", CASE "
                . " WHEN NVL(ACM.EQUIP_NRO, 0) = 0 "
                . " THEN 0 "
                . " ELSE 1 "
                . " END AS VERIF_CHECKLIST "
                . " FROM "
                . " PMM_ATUALIZACAO PA "
                . " , (SELECT EQUIP_NRO "
                . " FROM "
                . " ATUALIZA_CHECKLIST_MOBILE "
                . " WHERE "
                . " DT_MOBILE IS NULL) ACM "
                . " WHERE "
                . " PA.EQUIP_ID = " . $equip
                . " AND "
                . " PA.EQUIP_ID = ACM.EQUIP_NRO(+) ";

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }

    public function updAtual($equip, $va) {

        $sql = "UPDATE PMM_ATUALIZACAO "
                . " SET "
                . " VERSAO_ATUAL = TRIM(TO_CHAR(" . $va . ", '99999999D99'))"
                . " , DTHR_ULT_ATUAL = SYSDATE "
                . " WHERE "
                . " EQUIP_ID = " . $equip;

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    public function idCheckList($equip) {

        $select = " SELECT "
                . " NVL(C.PLMANPREV_ID, 0) AS IDCHECKLIST "
                . " FROM "
                . " USINAS.V_SIMOVA_EQUIP E "
                . " , USINAS.V_EQUIP_PLANO_CHECK C "
                . " WHERE  "
                . " E.NRO_EQUIP = " . $equip
                . " AND E.NRO_EQUIP = C.EQUIP_NRO(+) ";

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
    
    public function verAtualECM($equip) {

        $select = "SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " ECM_ATUALIZACAO "
                . " WHERE "
                . " EQUIP_ID = " . $equip;

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
    
     public function insAtualECM($equip, $va) {

        $sql = "INSERT INTO ECM_ATUALIZACAO ("
                . " EQUIP_ID "
                . " , VERSAO_ATUAL "
                . " , VERSAO_NOVA "
                . " , DTHR_ULT_ATUAL "
                . " ) "
                . " VALUES ("
                . " " . $equip
                . " , TRIM(TO_CHAR(" . $va . ", '99999999D99')) "
                . " , TRIM(TO_CHAR(" . $va . ", '99999999D99')) "
                . " , SYSDATE "
                . " )";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();

    }
    
    public function retAtualECM($equip) {

        $select = " SELECT "
                . " VERSAO_NOVA"
                . " , VERSAO_ATUAL"
                . " FROM "
                . " ECM_ATUALIZACAO "
                . " WHERE "
                . " EQUIP_ID = " . $equip;

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }
    
    public function updAtualNovaECM($equip, $va) {

        $sql = "UPDATE ECM_ATUALIZACAO "
                . " SET "
                . " VERSAO_ATUAL = TRIM(TO_CHAR(" . $va . ", '99999999D99'))"
                . " , VERSAO_NOVA = TRIM(TO_CHAR(" . $va . ", '99999999D99'))"
                . " , DTHR_ULT_ATUAL = SYSDATE "
                . " WHERE "
                . " EQUIP_ID = " . $equip;

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }
    
    public function verAtualCheckListECM($equip) {

        $select = " SELECT "
                . " PA.VERSAO_ATUAL"
                . ", CASE "
                . " WHEN NVL(ACM.EQUIP_NRO, 0) = 0 "
                . " THEN 0 "
                . " ELSE 1 "
                . " END AS VERIF_CHECKLIST "
                . " FROM "
                . " ECM_ATUALIZACAO PA "
                . " , (SELECT EQUIP_NRO "
                . " FROM "
                . " ATUALIZA_CHECKLIST_MOBILE "
                . " WHERE "
                . " DT_MOBILE IS NULL) ACM "
                . " WHERE "
                . " PA.EQUIP_ID = " . $equip
                . " AND "
                . " PA.EQUIP_ID = ACM.EQUIP_NRO(+) ";

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }
    
    public function updAtualECM($equip, $va) {

        $sql = "UPDATE ECM_ATUALIZACAO "
                . " SET "
                . " VERSAO_ATUAL = TRIM(TO_CHAR(" . $va . ", '99999999D99'))"
                . " , DTHR_ULT_ATUAL = SYSDATE "
                . " WHERE "
                . " EQUIP_ID = " . $equip;

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }
    
    public function idCheckListECM($equip) {

        $select = " SELECT "
                . " NVL(C.PLMANPREV_ID, 0) AS IDCHECKLIST "
                . " FROM "
                . " USINAS.V_SIMOVA_EQUIP E "
                . " , USINAS.V_EQUIP_PLANO_CHECK C "
                . " WHERE  "
                . " E.NRO_EQUIP = " . $equip
                . " AND E.NRO_EQUIP = C.EQUIP_NRO(+) ";

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
    
}
