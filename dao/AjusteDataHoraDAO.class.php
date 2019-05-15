<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'Conn.class.php';
/**
 * Description of AjusteDataHora
 *
 * @author anderson
 */
class AjusteDataHoraDAO extends Conn {

    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dataHoraIdBolPneu($idBolPneu, $dataHora){
        
        $this->Conn = parent::getConn();
        
        $select = " SELECT "
                    . " A.VERSAO_ATUAL AS VERSAO "
                . " FROM "
                    . " EQUIP E"
                    . " , PMM_ATUALIZACAO A "
                    . " , PMM_BOLETIM B "
                    . " , PMM_APONTAMENTO AP "
                    . " , PMM_BOLETIM_PNEU BP "
                . " WHERE "
                    . " BP.ID = " . $idBolPneu
                    . " AND "
                    . " AP.ID = BP.APONTAMENTO_ID "
                    . " AND "
                    . " B.ID = AP.BOLETIM_ID "
                    . " AND "
                    . " E.EQUIP_ID = B.EQUIP_ID "
                    . " AND "
                    . " A.EQUIP_ID = E.NRO_EQUIP ";

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $versao = $item['VERSAO'];
        }

        $valorVersao = floatval(trim($versao));
        
        if ($valorVersao < 1.22) {
            return $dthr = "(TO_DATE('" . $dataHora . "','DD/MM/YYYY HH24:MI') - 3/24)";
        } 
        elseif ($valorVersao < 2) {
            return $this->dataHoraAntigo($dataHora);
        }
        else{
            return $this->dataHoraGMT($dataHora);
        }

    }
    
    public function dataHoraIdApont($idApont, $dataHora){
        
        $this->Conn = parent::getConn();
        
        $select = " SELECT "
                    . " A.VERSAO_ATUAL AS VERSAO "
                . " FROM "
                    . " EQUIP E"
                    . " , PMM_ATUALIZACAO A "
                    . " , PMM_BOLETIM B "
                    . " , PMM_APONTAMENTO AP "
                . " WHERE "
                    . " AP.ID = " . $idApont
                    . " AND "
                    . " B.ID = AP.BOLETIM_ID "
                    . " AND "
                    . " E.EQUIP_ID = B.EQUIP_ID "
                    . " AND "
                    . " A.EQUIP_ID = E.NRO_EQUIP ";

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $versao = $item['VERSAO'];
        }

        $valorVersao = floatval(trim($versao));
        
        if ($valorVersao < 1.22) {
            return $dthr = "(TO_DATE('" . $dataHora . "','DD/MM/YYYY HH24:MI') - 3/24)";
        } 
        elseif ($valorVersao < 2) {
            return $this->dataHoraAntigo($dataHora);
        }
        else{
            return $this->dataHoraGMT($dataHora);
        }

    }
    
    public function dataHoraIdBoletim($idBoletim, $dataHora){
        
        $this->Conn = parent::getConn();
        
        $select = " SELECT "
                . " A.VERSAO_ATUAL AS VERSAO "
                . " FROM "
                . " EQUIP E"
                . " , PMM_ATUALIZACAO A "
                . " , PMM_BOLETIM B "
                . " WHERE "
                . " B.ID = " . $idBoletim
                . " AND "
                . " E.EQUIP_ID = B.EQUIP_ID "
                . " AND "
                . " A.EQUIP_ID = E.NRO_EQUIP ";

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $versao = $item['VERSAO'];
        }

        $valorVersao = floatval(trim($versao));
        
        if ($valorVersao < 1.22) {
            return $dthr = "(TO_DATE('" . $dataHora . "','DD/MM/YYYY HH24:MI') - 3/24)";
        } 
        elseif ($valorVersao < 2) {
            return $this->dataHoraAntigo($dataHora);
        }
        else{
            return $this->dataHoraGMT($dataHora);
        }

    }
    
    public function dataHoraIdEquip($idEquip, $dataHora){
        
        $this->Conn = parent::getConn();
        
        $select = " SELECT "
                . " A.VERSAO_ATUAL AS VERSAO "
                . " FROM "
                . " EQUIP E"
                . " , PMM_ATUALIZACAO A "
                . " WHERE "
                . " E.EQUIP_ID = " . $idEquip
                . " AND "
                . " A.EQUIP_ID = E.NRO_EQUIP ";

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $versao = $item['VERSAO'];
        }

        $valorVersao = floatval(trim($versao));
        
        if ($valorVersao < 1.22) {
            return $dthr = "(TO_DATE('" . $dataHora . "','DD/MM/YYYY HH24:MI') - 3/24)";
        } 
        elseif ($valorVersao < 2) {
            return $this->dataHoraAntigo($dataHora);
        }
        else{
            return $this->dataHoraGMT($dataHora);
        }

    }
    
    public function dataHoraNroEquip($nroEquip, $dataHora){
        
        $this->Conn = parent::getConn();
        
        $select = " SELECT "
                . " A.VERSAO_ATUAL AS VERSAO "
                . " FROM "
                . " PMM_ATUALIZACAO A "
                . " WHERE "
                . " A.EQUIP_ID = " . $nroEquip;

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $versao = $item['VERSAO'];
        }

        $valorVersao = floatval(trim($versao));
        
        if ($valorVersao < 1.22) {
            return $dthr = "(TO_DATE('" . $dataHora . "','DD/MM/YYYY HH24:MI') - 3/24)";
        } 
        elseif ($valorVersao < 2) {
            return $this->dataHoraAntigo($dataHora);
        }
        else{
            return $this->dataHoraGMT($dataHora);
        }

    }
    
    public function dataHoraGMT($dataHora) {

        $this->Conn = parent::getConn();
        
        $select = " SELECT "
                . " COUNT(ID) AS VERDATA "
                . " FROM "
                . " PERIODO_HORARIO_VERAO "
                . " WHERE "
                . " TO_DATE('" . $dataHora . "','DD/MM/YYYY HH24:MI') BETWEEN  DATA_INICIAL AND DATA_FINAL";

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $v = $item['VERDATA'];
        }

        if ($v == 0) {
            $dthr = "(TO_DATE('" . $dataHora . "','DD/MM/YYYY HH24:MI') - 3/24)";
        } else {
            $dthr = "(TO_DATE('" . $dataHora . "','DD/MM/YYYY HH24:MI') - 2/24)";
        }

        return $dthr;
    }
    
    public function dataHoraAntigo($dataHora) {

        $this->Conn = parent::getConn();
        
        $select = " SELECT "
                    . " ((SYSDATE - TO_DATE('" . $dataHora . "','DD/MM/YYYY HH24:MI')) * 1440) AS MINUTOS "
                . " FROM "
                    . " DUAL ";

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $min = $item['MINUTOS'];
        }

        if ($min < -20) {
            $dthr = "(TO_DATE('" . $dataHora . "','DD/MM/YYYY HH24:MI') - 1/24)";
        } else {
            $dthr = "TO_DATE('" . $dataHora . "','DD/MM/YYYY HH24:MI')";
        }

        return $dthr;
    }

}
