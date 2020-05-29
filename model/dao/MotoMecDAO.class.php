<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once ('../dbutil/Conn.class.php');
require_once ('../model/dao/AjusteDataHoraDAO.class.php');
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

        $select = " SELECT "
                . " OP.ID AS \"idMotoMec\" "
                . " , CASE "
                    . " WHEN OP.MOTPARADA_ID IS NOT NULL AND AA.ATIVAGR_ID IS NULL "
                    . " THEN OP.MOTPARADA_ID "
                    . " WHEN OP.MOTPARADA_ID IS NULL AND AA.ATIVAGR_ID IS NOT NULL "
                    . " THEN AA.ATIVAGR_ID "
                    . " ELSE 0 "
                    . " END AS \"idOperMotoMec\" "
                . " , CASE "
                    . " WHEN FUNCAO_COD = 1 "
                    . " THEN MP.DESCR "
                    . " ELSE FUOP.DESCR "
                    . " END AS \"descrOperMotoMec\" "
                . " , OP.FUNCAO_COD AS \"codFuncaoOperMotoMec\" "
                . " , OP.POSICAO AS \"posOperMotoMec\" "
                . " , OP.TIPO AS \"tipoOperMotoMec\" "
                . " , OP.APLIC AS \"aplicOperMotoMec\" "
                . " , CASE "
                    . " WHEN OP.MOTPARADA_ID IS NOT NULL AND AA.ATIVAGR_ID IS NULL "
                    . " THEN 2 "
                    . " WHEN OP.MOTPARADA_ID IS NULL AND AA.ATIVAGR_ID IS NOT NULL "
                    . " THEN 1 "
                    . " ELSE 0 "
                    . " END AS \"funcaoOperMotoMec\" "
                . " FROM " 
                . " OPCAO_MOTOMEC OP "
                . " , FUNCAO_OPCAO_MOTOMEC FUOP "
                . " , MOTIVO_PARADA MP "
                . " , ATIV_AGR AA "
                . " WHERE "
                . " OP.FUNCAO_COD = FUOP.COD "
                . " AND "
                . " OP.APLIC = FUOP.APLIC "
                . " AND "
                . " OP.MOTPARADA_ID = MP.MOTPARADA_ID(+) "
                . " AND "
                . " OP.ATIVAGR_ID = AA.ATIVAGR_ID(+) "
                . " ORDER BY " 
                . " OP.ID " 
                . " ASC ";
  
        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }
    
}
