<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
/**
 * Description of ApontaCarregDAO
 *
 * @author anderson
 */
class CarregDAO extends Conn {
    //put your code here

    /** @var PDOStatement */
    private $Read;
    /** @var PDO */
    private $Conn;
    
    public function cancelCarregProd($carreg) {
        
        $update = " UPDATE "
                . " USINAS.REG_COMPOSTO "
                . " SET "
                . " FLAG_CARREG = 2, CANCEL = 1 "
                . " WHERE "
                . " FLAG_CARREG = 1 AND "
                . " EQUIP_ID = " . $carreg->equipApontCarreg;
        
        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($update);
        $this->Create->execute();
        
    }
    
    public function verifCarregProd($carreg) {
        
        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " USINAS.REG_COMPOSTO "
                . " WHERE "
                . " DT = TO_DATE('" . $carreg->dataApontCarreg . "','DD/MM/YYYY HH24:MI') "
                . " AND "
                . " EQUIP_ID = " . $carreg->equipApontCarreg;
        
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
    
    public function insCarregProd($carreg) {
        
        $insert = "INSERT INTO USINAS.REG_COMPOSTO ("
                . " TIPO "
                . " , DT "
                . " , EQUIP_ID "
                . " , FUNC_ID "
                . " , PROD_ID "
                . " , FLAG_CARREG "
                . " ) "
                . " VALUES ("
                . " 0 "
                . " , TO_DATE('" . $carreg->dataApontCarreg . "','DD/MM/YYYY HH24:MI') "
                . " , " . $carreg->equipApontCarreg . " "
                . " , " . $carreg->motoApontCarreg . " "
                . " , " . $carreg->prodApontCarreg . " "
                . " , 1 "
                . " )";
        
        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($insert);
        $this->Create->execute();
        
    }
    
    public function cancelCarregComp($carreg) {
        
        $update = " UPDATE "
                . " USINAS.REG_COMPOSTO "
                . " SET "
                . " CANCEL = 1 "
                . " WHERE "
                . " EQUIP_ID = " . $carreg->equipApontCarreg;
        
        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($update);
        $this->Create->execute();
        
    }
    
    public function verifCarregComp($carreg) {
        
        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " USINAS.REG_COMPOSTO "
                . " WHERE "
                . " DT = TO_DATE('" . $carreg->dataApontCarreg . "','DD/MM/YYYY HH24:MI') "
                . " AND "
                . " EQUIP_ID = " . $carreg->equipApontCarreg;
        
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
    
    public function insCarregComp($carreg) {
        
        $select = " SELECT "
                . " OA.OSAGRICOLA_ID AS OSAGRICOLA "
                . " FROM "
                . " OS_AGRICOLA OA "
                . " , OS OS "
                . " WHERE "
                . " OS.OS_ID = OA.OS_ID "
                . " AND OS.OS_ID = " . $carreg->osApontCarreg;
        
        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();
        
        foreach ($result as $item) {
            $os = $item['OSAGRICOLA'];
        }
        
        $insert = "INSERT INTO USINAS.REG_COMPOSTO ("
                . " TIPO "
                . " , DT "
                . " , EQUIP_ID "
                . " , FUNC_ID "
                . " , LEIRA_ID "
                . " , OSAGRICOLA_ID "
                . " , FLAG_CARREG "
                . " , PROD_ID "
                . " ) "
                . " VALUES ("
                . " 1 "
                . " , TO_DATE('" . $carreg->dataApontCarreg . "','DD/MM/YYYY HH24:MI') "
                . " , " . $carreg->equipApontCarreg
                . " , " . $carreg->motoApontCarreg
                . " , " . $carreg->leiraApontCarreg
                . " , " . $os
                . " , 2 "
                . " , 76271 "
                . " )";
        
        $this->Create = $this->Conn->prepare($insert);
        $this->Create->execute();
        
    }
    
    public function updCarregProd($equip) {
        
        $update = " UPDATE "
                        . " USINAS.REG_COMPOSTO "
                    . " SET "
                        . " FLAG_CARREG = 2"
                    . " WHERE "
                        . " FLAG_CARREG = 1 AND"
                        . " EQUIP_ID = " . $equip;
        
        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($update);
        $this->Create->execute();
        
    }
    
    public function retLeiraCarregProd($equip) {
        
        $result = null;
        
        while(empty($result)) {
        
            $select = " SELECT "
                        . " C.EQUIP_ID AS \"equip\" "
                        . " , L.CD AS \"codLeira\" "
                        . " , C.ORDCARREG_ID AS \"idOrdCarreg\" "
                        . " , O.PESO_ENTRADA AS \"pesoEntrada\" "
                        . " , O.PESO_SAIDA AS \"pesoSaida\" "
                        . " , ABS(O.PESO_SAIDA - O.PESO_ENTRADA) AS \"pesoLiquido\" "
                    . " FROM "
                        . " USINAS.REG_COMPOSTO C "
                        . " , USINAS.LEIRA L "
                        . " , USINAS.ORD_CARREG O "
                    . " WHERE "
                        . " C.EQUIP_ID = " . $equip
                        . " AND "
                        . " C.FLAG_CARREG = 1 "
                        . " AND"
                        . " C.LEIRA_ID_DESCARGA = L.LEIRA_ID "
                        . " AND "
                        . " O.ORDCARREG_ID = C.ORDCARREG_ID ";
            
            $this->Conn = parent::getConn();
            $this->Read = $this->Conn->prepare($select);
            $this->Read->setFetchMode(PDO::FETCH_ASSOC);
            $this->Read->execute();
            $result = $this->Read->fetchAll();
            
        }
        
        return $result;
        
    }

    public function retCarregComp($equip) {
        
        $result = null;
        
        while(empty($result)) {
        
            $select = " SELECT "
                        . " C.EQUIP_ID AS \"equipCarreg\" "
                        . " , L.CD AS \"codLeiraCarreg\" "
                        . " , C.ORDCARREG_ID AS \"idOrdCarreg\" "
                        . " , O.PESO_ENTRADA AS \"pesoEntradaCarreg\" "
                        . " , O.PESO_SAIDA AS \"pesoSaidaCarreg\" "
                        . " , ABS(O.PESO_SAIDA - O.PESO_ENTRADA) AS \"pesoLiquidoCarreg\" "
                    . " FROM "
                        . " USINAS.REG_COMPOSTO C "
                        . " , USINAS.LEIRA L "
                        . " , USINAS.ORD_CARREG O "
                    . " WHERE "
                        . " C.EQUIP_ID = " . $equip
                        . " AND "
                        . " C.FLAG_CARREG = 2 "
                        . " AND"
                        . " C.LEIRA_ID_DESCARGA = L.LEIRA_ID "
                        . " AND "
                        . " O.ORDCARREG_ID = C.ORDCARREG_ID ";
            
            $this->Conn = parent::getConn();
            $this->Read = $this->Conn->prepare($select);
            $this->Read->setFetchMode(PDO::FETCH_ASSOC);
            $this->Read->execute();
            $result = $this->Read->fetchAll();
            
        }
        
        return $result;
        
    }
    
    public function retLeiraComp($equip, $os) {
        
        $idLeira = 0;
        $codLeira = null;
        
//        while (empty($cdLeira)) {
//            
//            $sql = "CALL pk_composto_auto.pkb_ret_leira(?, ?, ?, ?)";
//            $this->Conn = parent::getConn();
//            $stmt = $this->Conn->prepare($sql);
//            $stmt->bindParam(1, $equip, PDO::PARAM_INT, 32);
//            $stmt->bindParam(2, $os, PDO::PARAM_INT, 32);
//            $stmt->bindParam(3, $idLeira, PDO::PARAM_INT, 32);
//            $stmt->bindParam(4, $codLeira, PDO::PARAM_STR, 4000);
//            $stmt->execute();
//            $dado = array("equip" => $equip, "os" => $os
//                , "idLeira" => $idLeira, "codLeira" => $codLeira);
//            
//        }
        
            $dado = array("equip" => 2986, "os" => 1245061
                , "idLeira" => 1, "codLeira" => '02');
        
        return array($dado);
        
    }
    
    
}
