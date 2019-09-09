<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
require_once('../model/dao/AjusteDataHoraDAO.class.php');

/**
 * Description of BoletimMM
 *
 * @author anderson
 */
class BoletimMMDAO extends Conn {
    
    public function verifBoletimMM($bol) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PMM_BOLETIM "
                . " WHERE "
                . " DTHR_INICIAL_CEL = TO_DATE('" . $bol->dthrInicialBolMM . "','DD/MM/YYYY HH24:MI')"
                . " AND "
                . " EQUIP_ID = " . $bol->idEquipBolMM . " ";

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

    public function idBoletimMM($bol) {

        $select = " SELECT "
                . " ID AS ID "
                . " FROM "
                . " PMM_BOLETIM "
                . " WHERE "
                . " DTHR_INICIAL_CEL = TO_DATE('" . $bol->dthrInicialBolMM . "','DD/MM/YYYY HH24:MI')"
                . " AND "
                . " EQUIP_ID = " . $bol->idEquipBolMM . " ";

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $id = $item['ID'];
        }

        return $id;
    }

    public function insBoletimMMAberto($bol) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        if ($bol->hodometroInicialBolMM > 9999999) {
            $bol->hodometroInicialBolMM = 0;
        }

        $sql = "INSERT INTO PMM_BOLETIM ("
                . " FUNC_MATRIC "
                . " , EQUIP_ID "
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
                . " " . $bol->matricFuncBolMM
                . " , " . $bol->idEquipBolMM
                . " , " . $bol->idTurnoBolMM
                . " , " . $bol->hodometroInicialBolMM
                . " , " . $bol->osBolMM
                . " , " . $bol->ativPrincBolMM
                . " , " . $ajusteDataHoraDAO->dataHoraGMT($bol->dthrInicialBolMM)
                . " , TO_DATE('" . $bol->dthrInicialBolMM . "','DD/MM/YYYY HH24:MI') "
                . " , SYSDATE "
                . " , 1 "
                . " , " . $bol->statusConBolMM
                . " )";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    public function insBoletimMMFechado($bol) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        if ($bol->hodometroInicialBolMM > 9999999) {
            $bol->hodometroInicialBolMM = 0;
        }

        if ($bol->hodometroFinalBolMM > 9999999) {
            $bol->hodometroFinalBolMM = 0;
        }

        $sql = "INSERT INTO PMM_BOLETIM ("
                . " FUNC_MATRIC "
                . " , EQUIP_ID "
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
                . " " . $bol->matricFuncBolMM
                . " , " . $bol->idEquipBolMM
                . " , " . $bol->idTurnoBolMM
                . " , " . $bol->hodometroInicialBolMM
                . " , " . $bol->hodometroFinalBolMM
                . " , " . $bol->osBolMM
                . " , " . $bol->ativPrincBolMM
                . " , " . $ajusteDataHoraDAO->dataHoraGMT($bol->dthrInicialBolMM)
                . " , TO_DATE('" . $bol->dthrInicialBolMM . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " , " . $ajusteDataHoraDAO->dataHoraGMT($bol->dthrFinalBolMM)
                . " , TO_DATE('" . $bol->dthrFinalBolMM . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " , 2 "
                . " , " . $bol->statusConBolMM
                . " )";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    public function altBoletimMMFechado($idBol, $bol) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        if ($bol->hodometroFinalBolMM > 9999999) {
            $bol->hodometroFinalBolMM = 0;
        }

        $sql = "UPDATE PMM_BOLETIM "
                . " SET "
                . " HOD_HOR_FINAL = " . $bol->hodometroFinalBolMM
                . " , STATUS = " . $bol->statusBolMM
                . " , DTHR_FINAL = " . $ajusteDataHoraDAO->dataHoraGMT($bol->dthrFinalBolMM)
                . " , DTHR_FINAL_CEL = TO_DATE('" . $bol->dthrFinalBolMM . "','DD/MM/YYYY HH24:MI')"
                . " , DTHR_TRANS_FINAL = SYSDATE "
                . " WHERE "
                . " ID = " . $idBol;

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

}
