<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once ('./dbutil/Conn.class.php');
/**
 * Description of ROSAtivDAO
 *
 * @author anderson
 */
class ROSAtivDAO extends Conn {
    //put your code here
    
    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados($os) {

        $select = " SELECT "
                . " NRO_OS AS \"nroOS\" "
                . " , ATIVAGR_ID AS \"idAtiv\" "
                . " FROM "
                . " USINAS.V_PMM_OS "
                . " WHERE "
                . " NRO_OS = " . $os;
        
        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
        
    }
    
}
