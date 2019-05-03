<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'Conn.class.php';
/**
 * Description of AtualCheckListDAO
 *
 * @author anderson
 */
class VerifCheckList2DAO extends Conn {
    //put your code here
    
    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;
    
    public function dados($equip) {
        
        $this->Conn = parent::getConn();
        
        $select = " SELECT "
                . " E.EQUIP_ID AS \"idEquip\" "
                . " , E.NRO_EQUIP AS \"codEquip\" "
                . " , E.CLASSOPER_CD AS \"codClasseEquip\" "
                . " , CARACTER(E.CLASSOPER_DESCR) AS \"descrClasseEquip\" "
                . " , E.TPTUREQUIP_CD AS \"codTurno\" "
                . " , NVL(C.PLMANPREV_ID, 0) AS \"idChecklist\" "
                . " , CASE WHEN E.CLASSOPER_CD = 211 AND R.TP_EQUIP IS NULL THEN 4  "
                . " ELSE NVL(R.TP_EQUIP, 0) END AS \"tipoEquipFert\" "
                . " , NVL(PBH.HOD_HOR_FINAL, 0) AS \"horimetroEquip\" "
                . " FROM "
                . " USINAS.V_SIMOVA_EQUIP E "
                . " , USINAS.V_EQUIP_PLANO_CHECK C "
                . " , USINAS.ROLAO R "
                . " , (SELECT EQUIP_ID, HOD_HOR_FINAL FROM INTERFACE.PMM_BOLETIM PB WHERE PB.ID IN "
                . " (SELECT MAX(PB2.ID) FROM PMM_BOLETIM PB2 GROUP BY PB2.EQUIP_ID)) PBH "
                . " WHERE  "
                . " E.NRO_EQUIP = " . $equip
                . " AND E.NRO_EQUIP = C.EQUIP_NRO(+) "
                . " AND E.EQUIP_ID = R.EQUIP_ID(+) "
                . " AND E.EQUIP_ID = PBH.EQUIP_ID(+) ";
        
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $r1 = $this->Read->fetchAll();

        $dados = array("dados"=>$r1);
        $res1 = json_encode($dados);

        $select = " SELECT "
                        . " ITMANPREV_ID AS \"idItemChecklist\" "
                        . " , PLMANPREV_ID AS \"idChecklist\" "
                        . " , SEQ AS \"seqItemChecklist\" "
                        . " , CARACTER(PROC_OPER) AS \"descrItemChecklist\" "
                    . " FROM "
                        . " V_ITEM_PLANO_CHECK ";
        
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $r2 = $this->Read->fetchAll();
        
        $dados = array("dados"=>$r2);
        $res2 = json_encode($dados);
        
        return $res1 . "_" . $res2;
        
    }
    
}
