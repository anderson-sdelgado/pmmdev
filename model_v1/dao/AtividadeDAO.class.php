<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('./dbutil_v1/Conn.class.php');
/**
 * Description of AtividadeDAO
 *
 * @author anderson
 */
class AtividadeDAO extends Conn {
    //put your code here

    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados() {

        $select = " SELECT "
                        . " A.ATIVAGR_ID AS \"idAtiv\" "
                        . " , A.ATIVAGR_CD AS \"codAtiv\" "
                        . " , CARACTER(A.ATIVAGR_DESCR) AS \"descrAtiv\" "
                        . " , A.INFO_REND AS \"flagRendimento\" "
                        . " , A.INFO_CARREG AS \"flagTransbordo\" "
                        . " , A.INFO_IMPL AS \"flagImplemento\" "
                        . " , NVL(( SELECT  1 "
                                . " FROM "
                                . " USINAS.V_SIMOVA_EQUIP E "
                                . " , USINAS.ROLAO R " 
                                . " , V_SIMOVA_MODELO_ATIVAGR VA " 
                                . " , V_SIMOVA_ATIVAGR_NEW AA " 
                                . " WHERE "  
                                . " E.MODELEQUIP_ID = VA.MODELEQUIP_ID " 
                                . " AND " 
                                . " VA.ATIVAGR_CD = AA.ATIVAGR_CD "
                                . " AND "
                                . " R.TP_EQUIP = 1 "
                                . " AND "
                                . " E.EQUIP_ID = R.EQUIP_ID "
                                . " AND "
                                . " AA.ATIVAGR_CD = A.ATIVAGR_CD"
                                . " AND "
                                . " ROWNUM        = 1),0) AS \"flagCarretel\" "
                . " FROM "
                    . " USINAS.VMB_ATIVAGR_MECAN A ";

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }

}
