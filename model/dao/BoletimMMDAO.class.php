<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once './dbutil/Conn.class.php';
require_once './model/dao/AjusteDataHoraDAO.class.php';

/**
 * Description of BoletimMM
 *
 * @author anderson
 */
class BoletimMMDAO extends Conn {

    ///////////////////////////////////////////////VERSAO 2/////////////////////////////////////////////////////////////
    //    DADOS QUE VEM DAS PAGINAS INSERIRBOLABERTOMM2, INSERIRBOLFECHADOMM2 E INSERIRAPONTAMM2

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

        if ($bol->hodometroInicialBoletim > 9999999) {
            $bol->hodometroInicialBoletim = 0;
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
                . " " . $bol->codMotoBoletim
                . " , " . $bol->codEquipBoletim
                . " , " . $bol->codTurnoBoletim
                . " , " . $bol->hodometroInicialBoletim
                . " , " . $bol->osBoletim
                . " , " . $bol->ativPrincBoletim
                . " , " . $ajusteDataHoraDAO->dataHoraGMT($bol->dthrInicioBoletim)
                . " , TO_DATE('" . $bol->dthrInicioBoletim . "','DD/MM/YYYY HH24:MI') "
                . " , SYSDATE "
                . " , 1 "
                . " , " . $bol->statusConBoletim
                . " )";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
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
                . " , " . $ajusteDataHoraDAO->dataHoraGMT($bol->dthrInicioBoletim)
                . " , TO_DATE('" . $bol->dthrInicioBoletim . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " , " . $ajusteDataHoraDAO->dataHoraGMT($bol->dthrFimBoletim)
                . " , TO_DATE('" . $bol->dthrFimBoletim . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " , 2 "
                . " , " . $bol->statusConBoletim
                . " )";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    public function altBoletimMMFechado($idBol, $bol) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        if ($bol->hodometroFinalBoletim > 9999999) {
            $bol->hodometroFinalBoletim = 0;
        }

        $sql = "UPDATE PMM_BOLETIM "
                . " SET "
                . " HOD_HOR_FINAL = " . $bol->hodometroFinalBoletim
                . " , STATUS = " . $bol->statusBoletim
                . " , DTHR_FINAL = " . $ajusteDataHoraDAO->dataHoraGMT($bol->dthrFimBoletim)
                . " , DTHR_FINAL_CEL = TO_DATE('" . $bol->dthrFimBoletim . "','DD/MM/YYYY HH24:MI')"
                . " , DTHR_TRANS_FINAL = SYSDATE "
                . " WHERE "
                . " ID = " . $idBol;

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    ///////////////////////////////////////////////VERSAO 1 COM DATA DE CELULAR//////////////////////////////////////////////////////////
    //    DADOS QUE VEM DAS PAGINAS INSERIRBOLABERTODT, INSERIRBOLFECHADODT E INSERIRAPONTDT

    public function verifBoletimMMCDC($bol) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PMM_BOLETIM "
                . " WHERE "
                . " DTHR_INICIAL_CEL = TO_DATE('" . $bol->dthrInicioBoletim . "','DD/MM/YYYY HH24:MI')"
                . " AND "
                . " EQUIP_ID = " . $bol->codEquipBoletim;

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

    public function idBoletimMMCDC($bol) {

        $select = " SELECT "
                . " ID AS ID "
                . " FROM "
                . " PMM_BOLETIM "
                . " WHERE "
                . " DTHR_INICIAL_CEL = TO_DATE('" . $bol->dthrInicioBoletim . "','DD/MM/YYYY HH24:MI')"
                . " AND "
                . " EQUIP_ID = " . $bol->codEquipBoletim;

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

    public function insBoletimMMAbertoCDC($bol) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        if ($bol->hodometroInicialBoletim > 9999999) {
            $bol->hodometroInicialBoletim = 0;
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
                . " " . $bol->codMotoBoletim
                . " , " . $bol->codEquipBoletim
                . " , " . $bol->codTurnoBoletim
                . " , " . $bol->hodometroInicialBoletim
                . " , " . $bol->osBoletim
                . " , " . $bol->ativPrincBoletim
                . " , " . $ajusteDataHoraDAO->dataHoraAntigo($bol->dthrInicioBoletim)
                . " , TO_DATE('" . $bol->dthrInicioBoletim . "','DD/MM/YYYY HH24:MI') "
                . " , SYSDATE "
                . " , 1 "
                . " , " . $bol->statusConBoletim
                . " )";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    public function insBoletimMMFechadoCDC($bol) {

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
                . " , " . $ajusteDataHoraDAO->dataHoraAntigo($bol->dthrInicioBoletim)
                . " , TO_DATE('" . $bol->dthrInicioBoletim . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " , " . $ajusteDataHoraDAO->dataHoraAntigo($bol->dthrFimBoletim)
                . " , TO_DATE('" . $bol->dthrFimBoletim . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " , 2 "
                . " , " . $bol->statusConBoletim
                . " )";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    public function altBoletimMMFechadoCDC($idBol, $bol) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        if ($bol->hodometroFinalBoletim > 9999999) {
            $bol->hodometroFinalBoletim = 0;
        }

        $sql = "UPDATE PMM_BOLETIM "
                . " SET "
                . " HOD_HOR_FINAL = " . $bol->hodometroFinalBoletim
                . " , STATUS = " . $bol->statusBoletim
                . " , DTHR_FINAL = " . $ajusteDataHoraDAO->dataHoraAntigo($bol->dthrFimBoletim)
                . " , DTHR_FINAL_CEL = TO_DATE('" . $bol->dthrFimBoletim . "','DD/MM/YYYY HH24:MI')"
                . " , DTHR_TRANS_FINAL = SYSDATE "
                . " WHERE "
                . " ID = " . $idBol;

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    ///////////////////////////////////////////////SEM DATA DE CELULAR//////////////////////////////////////////////////////////
    //    DADOS QUE VEM DAS PAGINAS INSBOLABERTOMM, INSBOLFECHADOMM E INSAPONTMM

    public function verifBoletimMMSDC($bol) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PMM_BOLETIM "
                . " WHERE "
                . " DTHR_INICIAL = TO_DATE('" . $bol->dthrInicioBoletim . "','DD/MM/YYYY HH24:MI') "
                . " AND "
                . " EQUIP_ID = " . $bol->codEquipBoletim;

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

    public function idBoletimMMSDC($bol) {

        $select = " SELECT "
                . " ID AS ID "
                . " FROM "
                . " PMM_BOLETIM "
                . " WHERE "
                . " DTHR_INICIAL = TO_DATE('" . $bol->dthrInicioBoletim . "','DD/MM/YYYY HH24:MI') "
                . " AND "
                . " EQUIP_ID = " . $bol->codEquipBoletim . " ";

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

    public function insBoletimMMAbertoSDC($bol) {

        if ($bol->hodometroInicialBoletim > 9999999) {
            $bol->hodometroInicialBoletim = 0;
        }

        $sql = "INSERT INTO PMM_BOLETIM ("
                . " FUNC_MATRIC "
                . " , EQUIP_ID "
                . " , TURNO_ID "
                . " , HOD_HOR_INICIAL "
                . " , OS_NRO "
                . " , ATIVAGR_PRINC_ID "
                . " , DTHR_INICIAL "
                . " , DTHR_TRANS_INICIAL "
                . " , STATUS "
                . " ) "
                . " VALUES ("
                . " " . $bol->codMotoBoletim
                . " , " . $bol->codEquipBoletim
                . " , " . $bol->codTurnoBoletim
                . " , " . $bol->hodometroInicialBoletim
                . " , " . $bol->osBoletim
                . " , " . $bol->ativPrincBoletim
                . " , TO_DATE('" . $bol->dthrInicioBoletim . "','DD/MM/YYYY HH24:MI') "
                . " , SYSDATE "
                . " , 1 "
                . " )";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    public function insBoletimMMFechadoSDC($bol) {

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
                . " , DTHR_TRANS_INICIAL "
                . " , DTHR_FINAL "
                . " , DTHR_TRANS_FINAL "
                . " , STATUS "
                . " ) "
                . " VALUES ("
                . " " . $bol->codMotoBoletim
                . " , " . $bol->codEquipBoletim
                . " , " . $bol->codTurnoBoletim
                . " , " . $bol->hodometroInicialBoletim
                . " , " . $bol->hodometroFinalBoletim
                . " , " . $bol->osBoletim
                . " , " . $bol->ativPrincBoletim
                . " , TO_DATE('" . $bol->dthrInicioBoletim . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " , TO_DATE('" . $bol->dthrFimBoletim . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " , 2 "
                . " )";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    public function altBoletimMMFechadoSDC($idBol, $bol) {

        if ($bol->hodometroFinalBoletim > 9999999) {
            $bol->hodometroFinalBoletim = 0;
        }

        $sql = "UPDATE PMM_BOLETIM "
                . " SET "
                . " HOD_HOR_FINAL = " . $bol->hodometroFinalBoletim
                . " , STATUS = " . $bol->statusBoletim
                . " , DTHR_FINAL = TO_DATE('" . $bol->dthrFimBoletim . "','DD/MM/YYYY HH24:MI')"
                . " , DTHR_TRANS_FINAL = SYSDATE "
                . " WHERE "
                . " ID = " . $idBol;

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

}
