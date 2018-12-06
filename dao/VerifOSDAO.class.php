<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'Conn.class.php';
/**
 * Description of VerOSDAO
 *
 * @author anderson
 */
class VerifOSDAO extends Conn {
    //put your code here
    
    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados($valor) {

        $select = " SELECT DISTINCT "
                    . " NRO_OS AS \"nroOS\" "
                    . " , PROPRAGR_CD AS \"codProprOS\" "
                    . " , CARACTER(PROPRAGR_DESCR) AS \"descrProprOS\" "
                    . " , NVL(AREA_PROGR, 0) AS \"areaProgrOS\" "
                . " FROM "
                    . " USINAS.V_PMM_OS "
                . " WHERE "
                    . " NRO_OS = " . $valor
                    . " AND DT_INIC_PROGR <= SYSDATE " 
                    . " AND DT_FIM_PROGR >= SYSDATE - 1 " ;
        
        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $r1 = $this->Read->fetchAll();

        $dados = array("dados"=>$r1);
        $res1 = json_encode($dados);
        
        $select = " SELECT "
                . " NRO_OS AS \"nroOS\" "
                . " , ATIVAGR_CD AS \"codAtiv\" "
                . " FROM "
                . " USINAS.V_PMM_OS "
                . " WHERE "
                . " NRO_OS = " . $valor
                ;
        
        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $r2 = $this->Read->fetchAll();
        
        $dados = array("dados"=>$r2);
        $res2 = json_encode($dados);
        
        return $res1 . "#" . $res2;
    }
    
}
