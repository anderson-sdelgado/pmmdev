<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
require_once('../model/dao/AjusteDataHoraDAO.class.php');
/**
 * Description of MovLeiraDAO
 *
 * @author anderson
 */
class MovLeiraMMDAO extends Conn {
    //put your code here
    
    public function verifMovLeiraMM($idBol, $movLeira) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PMM_MOV_LEIRA "
                . " WHERE "
                . " LEIRA_ID = " . $movLeira->idLeira
                . " AND "
                . " DTHR_CEL = TO_DATE('" . $movLeira->dataHoraMovLeira . "','DD/MM/YYYY HH24:MI') "
                . " AND "
                . " BOLETIM_ID = " . $idBol;

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $v = $item['QTDE'];
        }

        return $v;
    }

    public function insMovLeiraMM($idBol, $movLeira) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

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
                . " , " . $movLeira->idLeira
                . " , " . $movLeira->tipoMovLeira
                . " , " . $ajusteDataHoraDAO->dataHoraGMT($movLeira->dataHoraMovLeira)
                . " , TO_DATE('" . $movLeira->dataHoraMovLeira. "','DD/MM/YYYY HH24:MI') "
                . " , SYSDATE "
                . " )";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }
    
}
