<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once ('./dbutil/Conn.class.php');

/**
 * Description of RAtivParada
 *
 * @author anderson
 */
class RAtivParadaDAO extends Conn {
    //put your code here

    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados() {

        $select = " SELECT "
                . " AA.ATIVAGR_ID AS \"idAtiv\" "
                . " , MOT.MOTPARADA_ID AS \"idParada\" "
                . " FROM "
                . " V_SIMOVA_ATIVAGR_NEW AA "
                . " , USINAS.R_ATIVAGR_MOTPARADA MOT "
                . " WHERE "
                . " MOT.ATIVAGR_ID = AA.ATIVAGR_ID "
                . " AND "
                . " AA.DESAT = 0 ";

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
        
    }

    public function dadosVersao1($equip) {

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
        
        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
        
    }
    
    public function verif($equip) {

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
        
        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
        
    }
    
    
}
