<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
/**
 * Description of PerdaDAO
 *
 * @author anderson
 */
class PerdaDAO extends Conn {
    //put your code here
    
    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados($matric) {

        $select = " SELECT " 
                        . " TO_CHAR(P.DT_REF, 'DD/MM/YYYY') AS \"dthrPerda\" "
                        . " , P.TOLETE AS \"toletePerda\" "
                        . " , P.LASCA AS \"lascaPerda\" "
                        . " , P.TOCO AS \"tocoPerda\" "
                        . " , P.PONTEIRO AS \"ponteiroPerda\" "
                        . " , P.CANAINTEIRA AS \"canaInteiraPerda\" "
                        . " , P.PEDACO AS \"pedacoPerda\" "
                        . " , P.REPIQUE AS \"repiquePerda\" "
                        . " , P.SOQUEIRA AS \"soqueiraPerda\" "
                        . " , P.NROSOQUEIRA AS \"nroSoqueiraPerda\" "
                        . " , P.TOTAL AS \"totalPerda\" "
                    . " FROM " 
                        . " VMB_PERDA_FRENTE P "
                        . " , VMB_FUNC_FRENTE FF "
                        . " , FUNC F "
                        . " , CORR C "
                    . " WHERE "
                        . " F.CD = " . $matric
                        . " AND "
                        . " P.FRENTE_ID = FF.FRENTE_ID "
                        . " AND "
                        . " FF.FUNC_ID = F.FUNC_ID "
                        . " AND "
                        . " F.CORR_ID = C.CORR_ID "
                    . " FETCH FIRST 1 ROWS ONLY ";
        
        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
        
    }
    
}
