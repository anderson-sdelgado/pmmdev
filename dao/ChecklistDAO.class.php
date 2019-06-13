<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'Conn.class.php';
/**
 * Description of ChecklistDAO
 *
 * @author anderson
 */
class ChecklistDAO extends Conn {
    //put your code here
    
    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados() {

        $select = " SELECT "
                        . " PLMANPREV_ID AS \"idChecklist\" "
                        . " , NRO_TURNO AS \"turnoChecklist\" "
                    . " FROM "
                        . " V_PLANO_CHECK "
                    . " WHERE "
                        . " NRO_TURNO IS NOT NULL";

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }
    
}
