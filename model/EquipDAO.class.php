<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
/**
 * Description of EquipDAO
 *
 * @author anderson
 */
class EquipDAO extends Conn {
    //put your code here

    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados($nroEquip) {

        $select = " SELECT "
                        . " E.EQUIP_ID AS \"idEquip\" "
                        . " , E.NRO_EQUIP AS \"nroEquip\" "
                        . " , E.CLASSEQUIP_CD AS \"classifEquip\" "
                        . " , E.CLASSOPER_CD AS \"codClasseEquip\" "
                        . " , CARACTER(E.CLASSOPER_DESCR) AS \"descrClasseEquip\" "
                        . " , E.TPTUREQUIP_CD AS \"codTurno\" "
                        . " , NVL(C.PLMANPREV_ID, 0) AS \"idCheckList\" "
                        . " , CASE WHEN E.CLASSOPER_CD = 211 AND R.TP_EQUIP IS NULL THEN 4  "
                        . " ELSE NVL(R.TP_EQUIP, 0) END AS \"tipoEquipFert\" "
                        //. " , NVL(PBH.HOD_HOR_FINAL, 0) AS \"horimetroEquip\" "
			. " , NVL(REPLACE(PBH.HOD_HOR_FINAL, ',', '.'), 0) AS \"horimetroEquip\" "
                        . " , CASE WHEN E.CLASSOPER_CD IN ( 2, 25, 200, 4 ) THEN 1 "
                        . " ELSE 0 END AS \"flagApontMecan\" "
                        . " , CASE WHEN E.CLASSOPER_CD IN ( 144, 146 ) THEN 1 "
                        . " ELSE 0 END AS \"flagApontPneu\" "
                    . " FROM "
                        . " V_EQUIP E "
                        . " , USINAS.V_EQUIP_PLANO_CHECK C "
                        . " , USINAS.ROLAO R "
                        . " , (SELECT EQUIP_ID, HOD_HOR_FINAL FROM INTERFACE.PMM_BOLETIM PB WHERE PB.ID IN "
                        . " (SELECT MAX(PB2.ID) FROM PMM_BOLETIM PB2 GROUP BY PB2.EQUIP_ID)) PBH "
                    . " WHERE  "
                        . " E.NRO_EQUIP = " . $nroEquip
                        . " AND "
                        . " E.NRO_EQUIP = C.EQUIP_NRO(+) "
                        . " AND "
                        . " E.EQUIP_ID = R.EQUIP_ID(+) "
                        . " AND "
                        . " E.EQUIP_ID = PBH.EQUIP_ID(+)"
                        . " AND "
                        . " E.TPTUREQUIP_CD IS NOT NULL ";

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
        
    }
    
    public function verifEquipNro($nroEquip) {

        $select = " SELECT "
                        . " COUNT(*) AS QTDE "
                    . " FROM "
                        . " V_EQUIP E "
                    . " WHERE  "
                        . " E.NRO_EQUIP = " . $nroEquip;

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
    
    public function retEquipNro($nroEquip) {

        $select = " SELECT "
                        . " E.EQUIP_ID AS ID "
                    . " FROM "
                        . " V_EQUIP E "
                    . " WHERE  "
                        . " E.NRO_EQUIP = " . $nroEquip;

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
    
    
    public function dadosSeg() {

        $select = " SELECT "
                        . " E.EQUIP_ID AS \"idEquip\" "
                        . " , E.NRO_EQUIP AS \"nroEquip\" "
                        . " , E.CLASSOPER_CD AS \"codClasseEquip\" "
                        . " , CARACTER(E.CLASSOPER_DESCR) AS \"descrClasseEquip\" "
                        . " , " 
                        . " CASE "
                            . " WHEN R.TP_EQUIP = 1 THEN 1 "
                            . " WHEN E.CLASSOPER_CD IN (4, 23, 227) THEN 2 "
                            . " WHEN E.CLASSOPER_CD IN (9, 6, 226) THEN 3 "
                            . " WHEN E.CLASSOPER_CD = 211 OR R.TP_EQUIP = 2 THEN 4 "
                            . " WHEN E.CLASSOPER_CD = 35 THEN 5 "
                            . " WHEN E.CLASSOPER_CD IN (5, 21, 36, 216) THEN 6 "
                        . " END AS \"tipoEquip\" "
                    . " FROM "
                        . " V_EQUIP E "
                        . " , USINAS.ROLAO R "
                    . " WHERE "
                        . " E.EQUIP_ID = R.EQUIP_ID(+) "
                        . " AND " 
                        . " (R.TP_EQUIP IN (1, 2) "
                        . " OR " 
                        . " E.CLASSOPER_CD IN (4, 5, 9, 6, 21, 23, 35, 36, 211, 216, 226, 227)) ";
        
        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }
    
    
    public function dadosPneu() {

        $select = " SELECT " 
                        . " E.EQUIP_ID AS \"idEquip\" "
                        . " , E.NRO_EQUIP AS \"nroEquip\""
                        . " , CARACTER(CO.DESCR) AS \"descrClasseEquip\""
                    . " FROM "
                        . " USINAS.EQUIP E"
                        . " , USINAS.CLASSE_OPER CO "
                    . " WHERE " 
                        . " DT_DESAT IS NULL "
                        . " AND "
                        . " E.CLASSOPER_ID = CO.CLASSOPER_ID ";

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
        
    }
    
    
}
