<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('./dbutil_v1/Conn.class.php');
require_once('./model_v1/dao/AjusteDataHoraDAO.class.php');

/**
 * Description of RendimentoMM
 *
 * @author anderson
 */
class RendimentoMMDAO extends Conn {

    ///////////////////////////////////////////////VERSAO 2/////////////////////////////////////////////////////////////
    //    DADOS QUE VEM DAS PAGINAS INSERIRBOLABERTOMM2, INSERIRBOLFECHADOMM2 E INSERIRAPONTAMM2

    public function verifRendimentoMM($idBol, $rend) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PMM_RENDIMENTO "
                . " WHERE "
                . " OS_NRO = " . $rend->nroOSRendimento
                . " AND "
                . " DTHR_CEL = TO_DATE('" . $rend->dthrRendimento . "','DD/MM/YYYY HH24:MI') "
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

    public function insRendimentoMM($idBol, $rend) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        $sql = "INSERT INTO PMM_RENDIMENTO ("
                . " BOLETIM_ID "
                . " , OS_NRO "
                . " , VL "
                . " , DTHR "
                . " , DTHR_CEL "
                . " , DTHR_TRANS "
                . " ) "
                . " VALUES ("
                . " " . $idBol
                . " , " . $rend->nroOSRendimento
                . " , " . $rend->valorRendimento
                . " , " . $ajusteDataHoraDAO->dataHoraGMT($rend->dthrRendimento)
                . " , TO_DATE('" . $rend->dthrRendimento . "','DD/MM/YYYY HH24:MI') "
                . " , SYSDATE "
                . " )";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    ///////////////////////////////////////////////VERSAO 1 COM DATA DE CELULAR//////////////////////////////////////////////////////////
    //    DADOS QUE VEM DAS PAGINAS INSERIRBOLABERTODT, INSERIRBOLFECHADODT E INSERIRAPONTDT

    public function verifRendimentoMMCDC($idBol, $rend) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PMM_RENDIMENTO "
                . " WHERE "
                . " OS_NRO = " . $rend->nroOSRendimento
                . " AND "
                . " DTHR_CEL = TO_DATE('" . $rend->dthrRendimento . "','DD/MM/YYYY HH24:MI') "
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

    public function insRendimentoMMCDC($idBol, $rend) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        $sql = "INSERT INTO PMM_RENDIMENTO ("
                . " BOLETIM_ID "
                . " , OS_NRO "
                . " , VL "
                . " , DTHR "
                . " , DTHR_CEL "
                . " , DTHR_TRANS "
                . " ) "
                . " VALUES ("
                . " " . $idBol
                . " , " . $rend->nroOSRendimento
                . " , " . $rend->valorRendimento
                . " , " . $ajusteDataHoraDAO->dataHoraAntigo($rend->dthrRendimento)
                . " , TO_DATE('" . $rend->dthrRendimento . "','DD/MM/YYYY HH24:MI') "
                . " , SYSDATE "
                . " )";


        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    ///////////////////////////////////////////////SEM DATA DE CELULAR//////////////////////////////////////////////////////////
    //    DADOS QUE VEM DAS PAGINAS INSBOLABERTOMM, INSBOLFECHADOMM E INSAPONTMM

    public function verifRendimentoMMSDC($idBol, $rend, $dthrBoletim) {

        if (!isset($rend->dthrRendimento) || empty($rend->dthrRendimento)) {

            $select = " SELECT "
                    . " COUNT(*) AS QTDE "
                    . " FROM "
                    . " PMM_RENDIMENTO "
                    . " WHERE "
                    . " OS_NRO = " . $rend->nroOSRendimento
                    . " AND "
                    . " DTHR_CEL = TO_DATE('" . $dthrBoletim . "','DD/MM/YYYY HH24:MI') "
                    . " AND "
                    . " BOLETIM_ID = " . $idBol;
        } else {

            $select = " SELECT "
                    . " COUNT(*) AS QTDE "
                    . " FROM "
                    . " PMM_RENDIMENTO "
                    . " WHERE "
                    . " OS_NRO = " . $rend->nroOSRendimento
                    . " AND "
                    . " DTHR_CEL = TO_DATE('" . $rend->dthrRendimento . "','DD/MM/YYYY HH24:MI') "
                    . " AND "
                    . " BOLETIM_ID = " . $idBol;
        }

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

    public function insRendimentoMMSDC($idBol, $rend, $dthrBoletim) {

        if (!isset($rend->dthrRendimento) || empty($rend->dthrRendimento)) {

            $sql = "INSERT INTO PMM_RENDIMENTO ("
                    . " BOLETIM_ID "
                    . " , OS_NRO "
                    . " , VL "
                    . " , DTHR "
                    . " , DTHR_TRANS "
                    . " ) "
                    . " VALUES ("
                    . " " . $idBol
                    . " , " . $rend->nroOSRendimento
                    . " , " . $rend->valorRendimento
                    . " , TO_DATE('" . $dthrBoletim . "','DD/MM/YYYY HH24:MI') "
                    . " , SYSDATE "
                    . " )";
        } else {

            $sql = "INSERT INTO PMM_RENDIMENTO ("
                    . " BOLETIM_ID "
                    . " , OS_NRO "
                    . " , VL "
                    . " , DTHR "
                    . " , DTHR_TRANS "
                    . " ) "
                    . " VALUES ("
                    . " " . $idBol
                    . " , " . $rend->nroOSRendimento
                    . " , " . $rend->valorRendimento
                    . " , TO_DATE('" . $rend->dthrRendimento . "','DD/MM/YYYY HH24:MI') "
                    . " , SYSDATE "
                    . " )";
        }

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

}
