<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'Conn.class.php';
/**
 * Description of AtualEquipAtivDAO
 *
 * @author anderson
 */
class AtualOSAtivDAO extends Conn {
    //put your code here
    
    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;
    
    public function dados($valor) {

        $select = " SELECT "
                . " ROWNUM AS \"idROSAtiv\" "
                . " , NRO_OS AS \"nroOS\" "
                . " , ATIVAGR_CD AS \"codAtiv\" "
                . " FROM "
                . " USINAS.V_SIMOVA_OS "
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
        
        return $res2;
        
    }
    
    
}
