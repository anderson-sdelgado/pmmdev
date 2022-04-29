<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once ('../dbutil/Conn.class.php');
/**
 * Description of MotoristaDAO
 *
 * @author anderson
 */
class MotoMecDAO extends Conn {
    //put your code here
    
    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados() {

                $select = "SELECT " 
                                . " OP.ID AS \"idMotoMec\" "
                                . " , CASE " 
                                    . " WHEN OP.MOTPARADA_ID IS NOT NULL "
                                    . " THEN OP.MOTPARADA_ID "
                                    . " ELSE 0 END AS \"idOperMotoMec\" "
                                . " , CARACTER(DOM.DESCR) AS \"descrOperMotoMec\" "
                                . " , OP.FUNCAO_COD AS \"codFuncaoOperMotoMec\" "
                                . " , OP.POSICAO AS \"posOperMotoMec\" "
                                . " , OP.TIPO AS \"tipoOperMotoMec\" "
                                . " , OP.APLIC AS \"aplicOperMotoMec\" "
                                . " , CASE "
                                    . " WHEN OP.MOTPARADA_ID IS NOT NULL "
                                    . " THEN 2 "
                                    . " ELSE 1 END AS \"funcaoOperMotoMec\" "
                            . " FROM " 
                                . " MENU_OPCAO_MOTOMEC OP " 
                                . " , MOTIVO_PARADA MP "
                                . " , DESCR_OPCAO_MOTOMEC DOM "
                            . " WHERE " 
                                . " OP.MOTPARADA_ID = MP.MOTPARADA_ID(+) "
                                . " AND "
                                . " OP.DESCR_OPCAO_ID = DOM.ID "
                            . " ORDER BY " 
                                . " OP.APLIC "
                                . " , OP.TIPO "
                                . " , OP.POSICAO "
                            . " ASC ";
  
        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }
    
}
