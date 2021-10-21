<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
/**
 * Description of RFuncaoAtivPar
 *
 * @author anderson
 */
class RFuncaoAtivParDAO extends Conn  {
    //put your code here
    
    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados($base) {

        $select = " SELECT " 
                    . " ATIVAGR_ID AS \"idAtivPar\" "
                    . " , DECODE(FLAG, 'FLAGRENDIMENTO', 1, 'FLAGTRANSBORDO', 2 "
                    . " , 'FLAGIMPLEMENTO', 3, 'FLAGCARRETEL', 4, 'FLAGLEIRA', 5"
                    . " , 'FLAGTRANSPCANA', 6) AS \"codFuncao\" "
                    . " , 1 AS \"tipoFuncao\" "
                . " FROM "
                    . " (SELECT " 
                    . " A.ATIVAGR_ID "
                    . " , CASE WHEN A.INFO_REND = 1 AND ATIVAGR_ID != 768 THEN 1 END AS FLAGRENDIMENTO "
                    . " , DECODE(A.INFO_CARREG, 1, 1) AS FLAGTRANSBORDO "
                    . " , DECODE(A.INFO_IMPL, 1, 1) AS FLAGIMPLEMENTO "
                    . " , DECODE(A.ATIVAGR_CD, 176, 1) AS FLAGLEIRA "
                    . " , DECODE(( SELECT 1 "
                        . " FROM "
                        . " USINAS.V_SIMOVA_EQUIP E "
                        . " , USINAS.ROLAO R "
                        . " , V_SIMOVA_MODELO_ATIVAGR VA "
                        . " , V_SIMOVA_ATIVAGR_NEW AA "
                . " WHERE "
                    . " E.MODELEQUIP_ID = VA.MODELEQUIP_ID " 
                    . " AND "
                    . " VA.ATIVAGR_CD = AA.ATIVAGR_CD "
                    . " AND "
                    . " R.TP_EQUIP = 1 "
                    . " AND "
                    . " E.EQUIP_ID = R.EQUIP_ID "
                    . " AND "
                    . " AA.ATIVAGR_CD = A.ATIVAGR_CD "
                    . " AND "
                    . " ROWNUM = 1), 1, 1) AS FLAGCARRETEL "
                    . " , DECODE(( SELECT 1 "
                    . " FROM "
                    . " V_SIMOVA_ATIVAGR_NEW AA "
                    . " WHERE "
                    . " AA.ATIVAGR_ID IN (557, 558, 559) "
                    . " AND "
                    . " AA.ATIVAGR_ID = A.ATIVAGR_ID "
                    . " AND "
                    . " ROWNUM = 1), 1, 1) AS FLAGTRANSPCANA "
                    . " FROM "
                    . " USINAS.VMB_ATIVAGR_MECAN A) P "
                    . " UNPIVOT "
                    . " (VALOR FOR FLAG IN "
                    . " (FLAGRENDIMENTO, FLAGTRANSBORDO "
                    . " , FLAGIMPLEMENTO, FLAGCARRETEL, FLAGLEIRA, FLAGTRANSPCANA) "
                    . " ) "
                . " UNION "
                . " SELECT "
                    . " MOTPARADA_ID AS \"idAtivPar\" "
                    . " , DECODE(FLAG, 'FLAGCHECKLIST', 1, 'FLAGIMPLEMENTO', 2"
                    . " , 'FLAGCALIBPNEU', 3, 'FLAGTROCAMOTORISTA', 4) AS \"codFuncao\" "
                    . " , 2 AS \"tipoFuncao\" "
                . " FROM "
                    . " (SELECT " 
                    . " MOTPARADA_ID "
                    . " , DECODE(CD, 515, 1) AS FLAGCHECKLIST "
                    . " , DECODE(CD, 33, 1) AS FLAGIMPLEMENTO "
                    . " , DECODE(CD, 66, 1) AS FLAGCALIBPNEU "
                    . " , DECODE(MOTPARADA_ID, 193, 1) AS FLAGTROCAMOTORISTA "
                    . " FROM "
                    . " USINAS.MOTIVO_PARADA ) M "
                    . " UNPIVOT "
                    . " (VALOR FOR FLAG IN "
                    . " (FLAGCHECKLIST, FLAGIMPLEMENTO, FLAGCALIBPNEU, FLAGTROCAMOTORISTA) "
                    . " )";
        
        $this->Conn = parent::getConn($base);
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
        
    }
    
}
