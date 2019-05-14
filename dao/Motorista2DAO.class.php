<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'Conn.class.php';
/**
 * Description of MotoristaDAO
 *
 * @author anderson
 */
class Motorista2DAO extends Conn {
    //put your code here
    
    //put your code here
    
    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados() {

        $select = " SELECT "
                    . " NRO_CRACHA AS \"codMotorista\" "
                    . " , FUNC_NOME AS \"nomeMotorista\" "
                . " FROM "
                    . " USINAS.V_SIMOVA_FUNC "
                . " ORDER BY "
                    . " NRO_CRACHA "
                . " ASC ";
        
        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }
    
}
