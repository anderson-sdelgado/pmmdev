<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
/**
 * Description of LeiraDAO
 *
 * @author anderson
 */
class LeiraDAO extends Conn {
    //put your code here
    
    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function verifMovLeiraMM($idBol, $movLeira, $base) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PMM_MOV_LEIRA "
                . " WHERE "
                . " TIPO = " . $movLeira->tipoMovLeira
                . " AND "
                . " LEIRA_ID = " . $movLeira->idLeiraMovLeira
                . " AND "
                . " DTHR_CEL = TO_DATE('" . $movLeira->dthrMovLeira . "','DD/MM/YYYY HH24:MI') "
                . " AND "
                . " BOLETIM_ID = " . $idBol;

        $this->Conn = parent::getConn($base);
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $v = $item['QTDE'];
        }

        return $v;
    
    }

    public function insMovLeiraMM($idBol, $movLeira, $base) {

        $sql = "INSERT INTO PMM_MOV_LEIRA ("
                . " BOLETIM_ID "
                . " , LEIRA_ID "
                . " , TIPO "
                . " , DTHR "
                . " , DTHR_CEL "
                . " , DTHR_TRANS "
                . " ) "
                . " VALUES ("
                . " " . $idBol
                . " , " . $movLeira->idLeiraMovLeira
                . " , " . $movLeira->tipoMovLeira
                . " , TO_DATE('" . $movLeira->dthrMovLeira . "','DD/MM/YYYY HH24:MI')"
                . " , TO_DATE('" . $movLeira->dthrMovLeira . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " )";

        $this->Conn = parent::getConn($base);
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
        
    }
    
    public function dados($base) {

        $select = " SELECT "
                    . " LEIRA_ID AS \"idLeira\" "
                    . " , CD AS \"codLeira\" "
                    . " , 0 AS \"statusLeira\" "
                  . " FROM USINAS.LEIRA";

        $this->Conn = parent::getConn($base);
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
        
    }
    
}
