<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once './dbutil/Conn.class.php';
require_once 'AjusteDataHoraDAO.class.php';
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
                . " DTHR_INICIAL_CEL = TO_DATE('" . $bol->dthrInicioBoletim . "','DD/MM/YYYY HH24:MI')"
                . " AND "
                . " EQUIP_ID = " . $bol->codEquipBoletim . " ";

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
                . " DTHR_INICIAL_CEL = TO_DATE('" . $bol->dthrInicioBoletim . "','DD/MM/YYYY HH24:MI')"
                . " AND "
                . " EQUIP_ID = " . $bol->codEquipBoletim . " ";

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $id = $item['ID'];
        }

        return $id;
    }

    public function insBoletimMMFechado($bol) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        if ($bol->hodometroInicialBoletim > 9999999) {
            $bol->hodometroInicialBoletim = 0;
        }

        if ($bol->hodometroFinalBoletim > 9999999) {
            $bol->hodometroFinalBoletim = 0;
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
                . " " . $bol->codMotoBoletim
                . " , " . $bol->codEquipBoletim
                . " , " . $bol->codTurnoBoletim
                . " , " . $bol->hodometroInicialBoletim
                . " , " . $bol->hodometroFinalBoletim
                . " , " . $bol->osBoletim
                . " , " . $bol->ativPrincBoletim
                . " , " . $ajusteDataHoraDAO->dataHoraIdEquip($bol->codEquipBoletim, $bol->dthrInicioBoletim)
                . " , TO_DATE('" . $bol->dthrInicioBoletim . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " , " . $ajusteDataHoraDAO->dataHoraIdEquip($bol->codEquipBoletim, $bol->dthrFimBoletim)
                . " , TO_DATE('" . $bol->dthrFimBoletim . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " , 2 "
                . " , " . $bol->statusConBoletim
                . " )";

        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

}
