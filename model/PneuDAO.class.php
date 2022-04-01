<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
/**
 * Description of PneuDAO
 *
 * @author anderson
 */
class PneuDAO extends Conn {
    //put your code here
    
    /** @var PDOStatement */
    private $Read;
    /** @var PDO */
    private $Conn;
    
    public function dados($base) {
        
        $select = " SELECT "
                        . " EQUIPCOMPO_ID AS \"idPneu\" "
                        . " , CD AS \"nroPneu\" "
                    . " FROM "
                        . " VMB_PNEU"
                    . " WHERE "
                        . " CD NOT LIKE '12043' "
                    . " ORDER BY "
                        . " EQUIPCOMPO_ID "
                    . " ASC ";
        
        $this->Conn = parent::getConn($base);
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();
        
        return $result;
        
    }
    
    public function pesq($valor, $base) {
        
        $select = " SELECT "
                        . " EQUIPCOMPO_ID AS \"idPneu\" "
                        . " , CD AS \"codPneu\" "
                    . " FROM "
                        . " VMB_PNEU "
                    . " WHERE "
                        . " CD LIKE '" . $valor . "'";
        
        $this->Conn = parent::getConn($base);
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();
        
        return $result;
        
    }
    
}
