<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
/**
 * Description of LeiraDAO
 *
 * @author anderson
 */
class LeiraDAO extends Conn {
    //put your code here
    
    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados() {

        $select = " SELECT "
                    . " LEIRA_ID AS \"idLeira\" "
                    . " , CD AS \"codLeira\" "
                    . " , 0 AS \"statusLeira\" "
                  . " FROM USINAS.LEIRA";

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }
    
    public function retLeiraComp($equip, $os) {
        
        $idLeira = 0;
        $codLeira = null;
        
        while (empty($cdLeira)) {
            
            $sql = "CALL pk_composto_auto.pkb_ret_leira(?, ?, ?, ?)";
            $this->Conn = parent::getConn();
            $stmt = $this->Conn->prepare($sql);
            $stmt->bindParam(1, $equip, PDO::PARAM_INT, 32);
            $stmt->bindParam(2, $os, PDO::PARAM_INT, 32);
            $stmt->bindParam(3, $idLeira, PDO::PARAM_INT, 32);
            $stmt->bindParam(4, $codLeira, PDO::PARAM_STR, 4000);
            $stmt->execute();
            $dado = array("equip" => $equip, "os" => $os
                , "idLeira" => $idLeira, "codLeira" => $codLeira);
            
        }
        
        return array($dado);
        
    }
    
}
