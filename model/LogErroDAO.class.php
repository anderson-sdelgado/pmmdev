<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
/**
 * Description of LogErroDAO
 *
 * @author anderson
 */
class LogErroDAO extends Conn {


    public function verifLogErro($erro, $base) {

        $select = " SELECT "
                        . " COUNT(*) AS QTDE "
                    . " FROM "
                        . " PMM_LOG_ERRO "
                    . " WHERE "
                        . " DTHR_CEL = TO_DATE('" . $erro->dthr . "','DD/MM/YYYY HH24:MI')"
                        . " AND "
                        . " EQUIP_ID = " . $erro->idEquip
                        . " AND "
                        . " CEL_ID = " . $erro->idLog;

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

    public function insLogErro($erro, $base) {

        $sql = "INSERT INTO PMM_LOG_ERRO ("
                . " CEL_ID "
                . " , EQUIP_ID "
                . " , DTHR_CEL "
                . " , DTHR_TRANS "
                . " , ERRO "
                . " ) "
                . " VALUES ("
                . " " . $erro->idLog
                . " , " . $erro->idEquip
                . " , TO_DATE('" . $erro->dthr . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " , ? "
                . " )";
 
        $this->Conn = parent::getConn($base);
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->bindParam(1, $erro->exception, PDO::PARAM_STR, 32000);
        $this->Create->execute();

    }
    
}
