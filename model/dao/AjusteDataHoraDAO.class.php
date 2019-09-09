<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
/**
 * Description of AjusteDataHora
 *
 * @author anderson
 */
class AjusteDataHoraDAO extends Conn {

    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dataHoraGMT($dataHora) {

        $this->Conn = parent::getConn();
        
        $select = " SELECT "
                . " COUNT(ID) AS VERDATA "
                . " FROM "
                . " PERIODO_HORARIO_VERAO "
                . " WHERE "
                . " TO_DATE('" . $dataHora . "','DD/MM/YYYY HH24:MI') BETWEEN  DATA_INICIAL AND DATA_FINAL";

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $v = $item['VERDATA'];
        }

        if ($v == 0) {
            $dthr = "(TO_DATE('" . $dataHora . "','DD/MM/YYYY HH24:MI') - 3/24)";
        } else {
            $dthr = "(TO_DATE('" . $dataHora . "','DD/MM/YYYY HH24:MI') - 2/24)";
        }

        return $dthr;
    }
    
    public function dataHoraAntigo($dataHora) {

        $this->Conn = parent::getConn();
        
        $select = " SELECT "
                    . " ((SYSDATE - TO_DATE('" . $dataHora . "','DD/MM/YYYY HH24:MI')) * 1440) AS MINUTOS "
                . " FROM "
                    . " DUAL ";

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $min = $item['MINUTOS'];
        }

        if ($min < - 20) {
            $dthr = "(TO_DATE('" . $dataHora . "','DD/MM/YYYY HH24:MI') - 1/24)";
        } else {
            $dthr = "TO_DATE('" . $dataHora . "','DD/MM/YYYY HH24:MI')";
        }

        return $dthr;
    }

}
