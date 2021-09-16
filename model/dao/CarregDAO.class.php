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
    
    public function cancelCarregProd($carreg, $base) {
        
        $update = " UPDATE "
                    . " USINAS.REG_COMPOSTO "
                . " SET "
                    . " FLAG_CARREG = 2, CANCEL = 1 "
                . " WHERE "
                    . " FLAG_CARREG = 1 "
                    . " AND "
                    . " EQUIP_ID = " . $carreg->equipCarreg;
        
        $this->Conn = parent::getConn($base);
        $this->Create = $this->Conn->prepare($update);
        $this->Create->execute();
        
    }
    
    public function verifCarregProd($carreg, $base) {
        
        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " USINAS.REG_COMPOSTO "
                . " WHERE "
                . " DT = TO_DATE('" . $carreg->dthrCarreg . "','DD/MM/YYYY HH24:MI') "
                . " AND "
                . " EQUIP_ID = " . $carreg->equipCarreg;
        
        $this->Conn = parent::getConn($base);
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();
        
        foreach ($result as $item) {
            $v = $item['QTDE'];
        }
        
        return $v;
        
    }
    
    public function insCarregProd($carreg, $base) {
        
        $select = " SELECT "
                    . " FUNC_ID AS IDFUNC "
                . " FROM "
                    . " USINAS.V_SIMOVA_FUNC "
                . " WHERE "
                    . " NRO_CRACHA = " . $carreg->motoCarreg;
        
        $this->Conn = parent::getConn($base);
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();
        
        foreach ($result as $item) {
            $idFunc = $item['IDFUNC'];
        }
        
        $insert = "INSERT INTO "
                . " USINAS.REG_COMPOSTO "
                . " ( "
                . " TIPO "
                . " , DT "
                . " , EQUIP_ID "
                . " , FUNC_ID "
                . " , PROD_ID "
                . " , FLAG_CARREG "
                . " ) "
                . " VALUES ("
                . " 0 "
                . " , TO_DATE('" . $carreg->dthrCarreg . "','DD/MM/YYYY HH24:MI') "
                . " , " . $carreg->equipCarreg
                . " , " . $idFunc
                . " , " . $carreg->prodCarreg
                . " , 1 "
                . " )";
        
        $this->Create = $this->Conn->prepare($insert);
        $this->Create->execute();
        
    }
    
    public function cancelCarregComp($carreg, $base) {
        
        $update = " UPDATE "
                    . " USINAS.REG_COMPOSTO "
                . " SET "
                    . " CANCEL = 1 "
                . " WHERE "
                    . " EQUIP_ID = " . $carreg->equipCarreg;
        
        $this->Conn = parent::getConn($base);
        $this->Create = $this->Conn->prepare($update);
        $this->Create->execute();
        
    }
    
    public function verifCarregComp($carreg, $base) {
        
        $select = " SELECT "
                    . " COUNT(*) AS QTDE "
                . " FROM "
                    . " USINAS.REG_COMPOSTO "
                . " WHERE "
                    . " DT = TO_DATE('" . $carreg->dthrCarreg . "','DD/MM/YYYY HH24:MI') "
                . " AND "
                    . " EQUIP_ID = " . $carreg->equipCarreg;
        
        $this->Conn = parent::getConn($base);
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();
        
        foreach ($result as $item) {
            $v = $item['QTDE'];
        }
        
        return $v;
        
    }
    
    public function updLeiraDescarreg($carreg, $base) {
        
        $update = " UPDATE "
                        . " USINAS.REG_COMPOSTO "
                    . " SET "
                        . " LEIRA_ID_DESCARGA = " . $carreg->idLeiraCarreg
                    . " WHERE "
                        . " REGCOMPOST_ID = " . $carreg->idRegCompostoCarreg;
        
        $this->Conn = parent::getConn($base);
        $this->Create = $this->Conn->prepare($update);
        $this->Create->execute();
        
    }
    
    public function insCarregComp($carreg, $base) {
        
        $ajusteDataHoraDAO = new AjusteDataHoraDAO();
        
        $select = " SELECT "
                    . " OA.OSAGRICOLA_ID AS OSAGRICOLA "
                . " FROM "
                    . " OS_AGRICOLA OA "
                    . " , OS OS "
                . " WHERE "
                    . " OS.OS_ID = OA.OS_ID "
                    . " AND "
                    . " OS.OS_ID = " . $carreg->osCarreg;
        
        $this->Conn = parent::getConn($base);
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();
        
        foreach ($result as $item) {
            $os = $item['OSAGRICOLA'];
        }
        
        $select = " SELECT "
                    . " FUNC_ID AS IDFUNC "
                . " FROM "
                    . " USINAS.V_SIMOVA_FUNC "
                . " WHERE "
                    . " NRO_CRACHA = " . $carreg->motoCarreg;
        
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();
        
        foreach ($result as $item) {
            $idFunc = $item['IDFUNC'];
        }
        
        $insert = "INSERT INTO "
                . " USINAS.REG_COMPOSTO "
                . " ( "
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
                . " , TO_DATE('" . $carreg->dthrCarreg . "','DD/MM/YYYY HH24:MI') "
                . " , " . $carreg->equipCarreg
                . " , " . $idFunc
                . " , " . $carreg->idLeiraCarreg
                . " , " . $os
                . " , 2 "
                . " , 76271 "
                . " )";
        
        $this->Create = $this->Conn->prepare($insert);
        $this->Create->execute();
        
    }
    
    public function updCarregProd($equip, $base) {
        
        $update = " UPDATE "
                        . " USINAS.REG_COMPOSTO "
                    . " SET "
                        . " FLAG_CARREG = 2"
                    . " WHERE "
                        . " FLAG_CARREG = 1 "
                        . " AND"
                        . " EQUIP_ID = " . $equip;
        
        $this->Conn = parent::getConn($base);
        $this->Create = $this->Conn->prepare($update);
        $this->Create->execute();
        
    }
    
    public function retCarreg($equip, $base) {
        
        $result = null;
        
        while(empty($result)) {
        
            $select = " SELECT "
                        . " C.EQUIP_ID AS \"equipCarreg\" "
                        . " , C.REGCOMPOST_ID AS \"idRegCompostoCarreg\" "
                        . " , C.ORDCARREG_ID AS \"idOrdCarreg\" "
                        . " , TO_CHAR(C.DT, 'DD/MM/YYYY HH24:MI') AS \"dthrCarreg\" "
                        . " , O.PESO_ENTRADA AS \"pesoEntradaCarreg\" "
                        . " , O.PESO_SAIDA AS \"pesoSaidaCarreg\" "
                        . " , ABS(O.PESO_SAIDA - O.PESO_ENTRADA) AS \"pesoLiquidoCarreg\" "
                    . " FROM "
                        . " USINAS.REG_COMPOSTO C "
                        . " , USINAS.ORD_CARREG O "
                    . " WHERE "
                        . " C.EQUIP_ID = " . $equip
                        . " AND "
                        . " C.CANCEL = 0 "
                        . " AND "
                        . " O.ORDCARREG_ID = C.ORDCARREG_ID ";
            
            $this->Conn = parent::getConn($base);
            $this->Read = $this->Conn->prepare($select);
            $this->Read->setFetchMode(PDO::FETCH_ASSOC);
            $this->Read->execute();
            $result = $this->Read->fetchAll();
            
        }
        
        return $result;
        
    }
    
}
