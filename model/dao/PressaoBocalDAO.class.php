<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
/**
 * Description of PressaoBocal2DAO
 *
 * @author anderson
 */
class PressaoBocalDAO extends Conn {
    //put your code here
    
    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados() {

        $select = " SELECT "
                . " BOCALPRESS_ID AS \"idPressaoBocal\" "
                . " , BOCALBOMBA_ID AS \"idBocal\" "
                . " , PRESSAO_KGF_CM2 AS \"valorPressao\" "
                . " , VELOC_MH AS \"valorVeloc\" "
                . " FROM " 
                . " USINAS.VMB_BOCAL_BOMBA ";

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
        
    }
    
}
