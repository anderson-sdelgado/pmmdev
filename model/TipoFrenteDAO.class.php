<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
/**
 * Description of TipoFrenteDAO
 *
 * @author anderson
 */
class TipoFrenteDAO extends Conn {
    //put your code here
    
    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;
    
    public function dados($matric, $base) {
        
        $tipoFrente = 0;
        
        $select = " SELECT " 
                    . " FR.TP_FRENTE AS TIPO_FRENTE"
                    . " FROM "
                    . " VMB_FUNC_FRENTE FF "
                    . " , FUNC F "
                    . " , CORR C "
                    . " , FRENTE FR "
                    . " WHERE "
                    . " F.CD = " . $matric
                    . " AND "
                    . " FF.FRENTE_ID = FR.FRENTE_ID " 
                    . " AND "
                    . " FF.FUNC_ID = F.FUNC_ID "
                    . " AND "
                    . " F.CORR_ID = C.CORR_ID ";
    
        $this->Conn = parent::getConn($base);
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $doc) {
            $tipoFrente = $doc['TIPO_FRENTE'];
        }
        
        return $tipoFrente;
        
    }
    
}
