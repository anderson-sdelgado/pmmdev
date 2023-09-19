<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */
require_once('../dbutil/Conn.class.php');
/**
 * Description of BoletimPneuDAO
 *
 * @author anderson
 */
class BoletimPneuDAO extends Conn {

    public function verifBoletimPneu($idBol, $bolPneu) {

        $select = " SELECT "
                        . " COUNT(*) AS QTDE "
                    . " FROM "
                        . " PMP_BOLETIM "
                    . " WHERE "
                        . " BOLETIM_ID = " . $idBol
                        . " AND "
                        . " CEL_ID = " . $bolPneu->idBolPneu;

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

    public function idBoletimPneu($idBol, $bolPneu) {

        $select = " SELECT "
                        . " ID AS ID "
                    . " FROM "
                        . " PMP_BOLETIM "
                    . " WHERE "
                        . " BOLETIM_ID = " . $idBol
                        . " AND "
                        . " CEL_ID = " . $bolPneu->idBolPneu;

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

    public function insBoletimPneu($idBol, $bolPneu, $tipoAplic) {

        $select = " SELECT "
            . " FUNC_ID "
        . " FROM "
            . " USINAS.FUNC "
        . " WHERE "
            . " CD = " . $bolPneu->matricFuncBolPneu;

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $funcId = $item['FUNC_ID'];
        }
        
        $sql = " INSERT INTO PMP_BOLETIM ( "
                    . " BOLETIM_ID "
                    . " , FUNC_ID "
                    . " , EQUIP_ID "
                    . " , TIPO_APLIC "
                    . " , DTHR "
                    . " , DTHR_CEL "
                    . " , DTHR_TRANS "
                    . " , CEL_ID "
                . " ) "
                . " VALUES ( "
                    . " " . $idBol
                    . " , " . $funcId
                    . " , " . $bolPneu->idEquipBolPneu
                    . " , " . $tipoAplic
                    . " , TO_DATE('" . $bolPneu->dthrBolPneu . "','DD/MM/YYYY HH24:MI') "
                    . " , TO_DATE('" . $bolPneu->dthrBolPneu . "','DD/MM/YYYY HH24:MI') "
                    . " , SYSDATE "
                    . " , " . $bolPneu->idBolPneu
                . " ) ";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
        
    }
    
}
