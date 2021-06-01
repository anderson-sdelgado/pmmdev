<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
require_once('../model/dao/AjusteDataHoraDAO.class.php');
/**
 * Description of BoletimFertDAO
 *
 * @author anderson
 */
class BoletimFertDAO extends Conn {

    public function verifBoletimFert($bol, $base) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PMM_BOLETIM_FERT "
                . " WHERE "
                . " DTHR_INICIAL_CEL = TO_DATE('" . $bol->dthrInicialBolFert . "','DD/MM/YYYY HH24:MI')"
                . " AND "
                . " EQUIP_ID = " . $bol->idEquipBolFert . " ";

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

    public function idBoletimFert($bol, $base) {

        $select = " SELECT "
                . " ID AS ID "
                . " FROM "
                . " PMM_BOLETIM_FERT "
                . " WHERE "
                . " DTHR_INICIAL_CEL = TO_DATE('" . $bol->dthrInicialBolFert . "','DD/MM/YYYY HH24:MI')"
                . " AND "
                . " EQUIP_ID = " . $bol->idEquipBolFert . " ";

        $this->Conn = parent::getConn($base);
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $id = $item['ID'];
        }

        return $id;
    }

    public function insBoletimFertAberto($bol, $base) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        if ($bol->hodometroInicialBolFert > 9999999) {
            $bol->hodometroInicialBolFert = 0;
        }
        
        if ($bol->idEquipBolFert == $bol->idEquipBombaBolFert) {
            $motoBomba = null;
        }
        else{
            $motoBomba = $bol->idEquipBombaBolFert;
        }

        $sql = "INSERT INTO PMM_BOLETIM_FERT ("
                . " FUNC_MATRIC "
                . " , EQUIP_ID "
                . " , EQUIP_BOMBA_ID "
                . " , TURNO_ID "
                . " , HOD_HOR_INICIAL "
                . " , OS_NRO "
                . " , ATIVAGR_PRINC_ID "
                . " , DTHR_INICIAL "
                . " , DTHR_INICIAL_CEL "
                . " , DTHR_TRANS_INICIAL "
                . " , STATUS "
                . " , STATUS_CONEXAO "
                . " ) "
                . " VALUES ("
                . " " . $bol->matricFuncBolFert
                . " , " . $bol->idEquipBolFert
                . " , " . $motoBomba
                . " , " . $bol->idTurnoBolFert
                . " , " . $bol->hodometroInicialBolFert
                . " , " . $bol->osBolFert
                . " , " . $bol->ativPrincBolFert
                . " , " . $ajusteDataHoraDAO->dataHoraGMT($bol->dthrInicialBolFert, $base)
                . " , TO_DATE('" . $bol->dthrInicialBolFert . "','DD/MM/YYYY HH24:MI') "
                . " , SYSDATE "
                . " , 1 "
                . " , " . $bol->statusConBolFert
                . " )";

        $this->Conn = parent::getConn($base);
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    public function insBoletimFertFechado($bol, $base) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        if ($bol->hodometroInicialBolFert > 9999999) {
            $bol->hodometroInicialBolFert = 0;
        }

        if ($bol->hodometroFinalBolFert > 9999999) {
            $bol->hodometroFinalBolFert = 0;
        }

        $sql = "INSERT INTO PMM_BOLETIM_FERT ("
                . " FUNC_MATRIC "
                . " , EQUIP_ID "
                . " , EQUIP_BOMBA_ID "
                . " , TURNO_ID "
                . " , HOD_HOR_INICIAL "
                . " , HOD_HOR_FINAL "
                . " , OS_NRO "
                . " , ATIVAGR_PRINC_ID "
                . " , DTHR_INICIAL "
                . " , DTHR_INICIAL_CEL "
                . " , DTHR_TRANS_INICIAL "
                . " , DTHR_FINAL "
                . " , DTHR_FINAL_CEL "
                . " , DTHR_TRANS_FINAL "
                . " , STATUS "
                . " , STATUS_CONEXAO "
                . " ) "
                . " VALUES ("
                . " " . $bol->matricFuncBolFert
                . " , " . $bol->idEquipBolFert
                . " , " . $bol->idEquipBombaBolFert
                . " , " . $bol->idTurnoBolFert
                . " , " . $bol->hodometroInicialBolFert
                . " , " . $bol->hodometroFinalBolFert
                . " , " . $bol->osBolFert
                . " , " . $bol->ativPrincBolFert
                . " , " . $ajusteDataHoraDAO->dataHoraGMT($bol->dthrInicialBolFert, $base)
                . " , TO_DATE('" . $bol->dthrInicialBolFert . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " , " . $ajusteDataHoraDAO->dataHoraGMT($bol->dthrFinalBolFert, $base)
                . " , TO_DATE('" . $bol->dthrFinalBolFert . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " , 2 "
                . " , " . $bol->statusConBolFert
                . " )";

        $this->Conn = parent::getConn($base);
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    public function updateBoletimFertFechado($bol, $base) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        if ($bol->hodometroFinalBolFert > 9999999) {
            $bol->hodometroFinalBolFert = 0;
        }

        $sql = "UPDATE PMM_BOLETIM_FERT "
                . " SET "
                . " HOD_HOR_FINAL = " . $bol->hodometroFinalBolFert
                . " , STATUS = " . $bol->statusBolFert
                . " , DTHR_FINAL = " . $ajusteDataHoraDAO->dataHoraGMT($bol->dthrFinalBolFert, $base)
                . " , DTHR_FINAL_CEL = TO_DATE('" . $bol->dthrFinalBolFert . "','DD/MM/YYYY HH24:MI')"
                . " , DTHR_TRANS_FINAL = SYSDATE "
                . " WHERE "
                . " ID = " . $bol->idExtBolFert;

        $this->Conn = parent::getConn($base);
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

}
