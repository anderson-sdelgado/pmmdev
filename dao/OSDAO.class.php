<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'Conn.class.php';
/**
 * Description of OSDAO
 *
 * @author anderson
 */
class OSDAO extends Conn {
    //put your code here
    
    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados() {

        $select = " SELECT DISTINCT "
                    . " NRO_OS AS \"nroOS\" "
                    . " , NVL(PROPRAGR_CD, 0)  AS \"codProprOS\" "
                    . " , NVL(CARACTER(PROPRAGR_DESCR), 'ESTRUTURAL') AS \"descrProprOS\" "
                    . " , NVL(AREA_PROGR, 0) AS \"areaProgrOS\" "
                . " FROM "
                    . " USINAS.V_PMM_OS "
                . " WHERE "
                    . " DT_INIC_PROGR <= SYSDATE " 
                    . " AND DT_FIM_PROGR >= SYSDATE " ;
        
        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }
    
}
