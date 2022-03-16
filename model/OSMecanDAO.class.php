<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */
require_once ('../dbutil/Conn.class.php');
/**
 * Description of OSMecan
 *
 * @author anderson
 */
class OSMecanDAO extends Conn {
    //put your code here    
    
    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados($os, $base) {

        $select = " SELECT "
                    . " OS_ID AS \"idOS\" "
                    . " , NRO AS \"nroOS\" "
                    . " , NRO_EQUIP AS \"equipOS\" "
                    . " , DESCR AS \"descrEquipOS\" "
                . " FROM "
                    . " VMB_OS_AUTO "
                . " WHERE "
                    . " NRO = " . $os;

        $this->Conn = parent::getConn($base);
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }
    
}
