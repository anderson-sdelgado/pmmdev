<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'Conn.class.php';
/**
 * Description of AtualEquipAtivDAO
 *
 * @author anderson
 */
class VerifAtiv2DAO extends Conn {
    //put your code here
    
    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;
    
    public function dados($os, $equip) {

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
                    . " ROWNUM AS \"idEquipAtiv\" "
                    . " , VE.NRO_EQUIP AS \"codEquip\" "
                    . " , VA.ATIVAGR_CD AS \"codAtiv\" "
                . " FROM "
                    . " V_SIMOVA_EQUIP VE "
                    . " , V_SIMOVA_MODELO_ATIVAGR VA "
                    . " , V_SIMOVA_ATIVAGR_NEW AA "
                . " WHERE "
                . " VE.NRO_EQUIP = " . $equip
                . " AND "
                . " VE.MODELEQUIP_ID = VA.MODELEQUIP_ID "
                . " AND "
                . " VA.ATIVAGR_CD = AA.ATIVAGR_CD "
                . " AND "
                . " AA.DESAT = 0 " 
                . " ORDER BY ROWNUM ASC "
                ;
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $r2 = $this->Read->fetchAll();
        
        $dados = array("dados"=>$r2);
        $res2 = json_encode($dados);

        $select = " SELECT " 
                    . " ROWNUM AS \"idRAtivParada\" "
                    . " , AA.ATIVAGR_ID AS \"idAtiv\" "
                    . " , MOT.MOTPARADA_ID AS \"idParada\" "
                    . " FROM " 
                    . " V_SIMOVA_EQUIP VE " 
                    . " , V_SIMOVA_MODELO_ATIVAGR VA " 
                    . " , V_SIMOVA_ATIVAGR_NEW AA " 
                    . " , USINAS.R_ATIVAGR_MOTPARADA MOT " 
                    . " WHERE " 
                    . " VE.NRO_EQUIP = " . $equip
                    . " AND " 
                    . " VE.MODELEQUIP_ID = VA.MODELEQUIP_ID " 
                    . " AND " 
                    . " VA.ATIVAGR_CD = AA.ATIVAGR_CD " 
                    . " AND " 
                    . " MOT.ATIVAGR_ID = AA.ATIVAGR_ID " 
                    . " AND " 
                    . " AA.DESAT = 0 ";
        
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $r3 = $this->Read->fetchAll();
        
        $dados = array("dados"=>$r3);
        $res3 = json_encode($dados);
        
        $select = " SELECT DISTINCT "
                    . " NRO_OS AS \"nroOS\" "
                    . " , PROPRAGR_CD AS \"codProprOS\" "
                    . " , CARACTER(PROPRAGR_DESCR) AS \"descrProprOS\" "
                    . " , NVL(AREA_PROGR, 0) AS \"areaProgrOS\" "
                . " FROM "
                    . " USINAS.V_PMM_OS "
                . " WHERE "
                    . " NRO_OS = " . $os
//                    . " AND DT_INIC_PROGR <= SYSDATE " 
//                    . " AND DT_FIM_PROGR >= SYSDATE - 1"
                    . " " ;
        
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $r4 = $this->Read->fetchAll();

        $dados = array("dados"=>$r4);
        $res4 = json_encode($dados);
        
        $select = " SELECT "
                . " NRO_OS AS \"nroOS\" "
                . " , ATIVAGR_CD AS \"codAtiv\" "
                . " FROM "
                . " USINAS.V_PMM_OS "
                . " WHERE "
                . " NRO_OS = " . $os
                ;
        
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $r5 = $this->Read->fetchAll();
        
        $dados = array("dados"=>$r5);
        $res5 = json_encode($dados);
        
        return $res1 . "_" . $res2 . "|" . $res3 . "#" . $res4 . "?" . $res5;
        
    }
    
    
}
