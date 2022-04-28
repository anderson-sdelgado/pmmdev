<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once ('../dbutil/Conn.class.php');
/**
 * Description of ApontMecanDAO
 *
 * @author anderson
 */
class ApontMecanDAO extends Conn {

    //put your code here

    public function verifApontMecan($idBol, $apontMecan) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PMM_APONT_MECAN "
                . " WHERE "
                . " ID_CEL = " . $apontMecan->idApontMecan
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

    public function insApontMecanAberto($idBol, $apontMecan) {

        $sql = "INSERT INTO PMM_APONT_MECAN ("
                . " BOLETIM_ID "
                . " , OS_NRO "
                . " , ITEM_OS "
                . " , DTHR_INICIAL "
                . " , DTHR_CEL_INICIAL "
                . " , DTHR_TRANS_INICIAL "
                . " , DTHR_FINAL "
                . " , DTHR_CEL_FINAL "
                . " , DTHR_TRANS_FINAL "
                . " , ID_CEL "
                . " ) "
                . " VALUES ("
                . " " . $idBol
                . " , " . $apontMecan->osApontMecan
                . " , " . $apontMecan->itemOSApontMecan
                . " , TO_DATE('" . $apontMecan->dthrInicialApontMecan . "','DD/MM/YYYY HH24:MI')"
                . " , TO_DATE('" . $apontMecan->dthrInicialApontMecan . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " , NULL "
                . " , NULL "
                . " , NULL "
                . " , " . $apontMecan->idApontMecan
                . " )";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    public function insApontMecanFechado($idBol, $apontMecan) {

        $sql = "INSERT INTO PMM_APONT_MECAN ("
                . " BOLETIM_ID "
                . " , OS_NRO "
                . " , ITEM_OS "
                . " , DTHR_INICIAL "
                . " , DTHR_CEL_INICIAL "
                . " , DTHR_TRANS_INICIAL "
                . " , DTHR_FINAL "
                . " , DTHR_CEL_FINAL "
                . " , DTHR_TRANS_FINAL "
                . " , ID_CEL "
                . " ) "
                . " VALUES ("
                . " " . $idBol
                . " , " . $apontMecan->osApontMecan
                . " , " . $apontMecan->itemOSApontMecan
                . " , TO_DATE('" . $apontMecan->dthrInicialApontMecan . "','DD/MM/YYYY HH24:MI')"
                . " , TO_DATE('" . $apontMecan->dthrInicialApontMecan . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " , TO_DATE('" . $apontMecan->dthrFinalApontMecan . "','DD/MM/YYYY HH24:MI')"
                . " , TO_DATE('" . $apontMecan->dthrFinalApontMecan . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " , " . $apontMecan->idApontMecan
                . " )";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    public function updateApontMecan($idBol, $apontMecan) {

        $sql = "UPDATE PMM_APONT_MECAN "
                . " SET "
                . " DTHR_FINAL= TO_DATE('" . $apontMecan->dthrFinalApontMecan . "','DD/MM/YYYY HH24:MI') "
                . " , DTHR_CEL_FINAL = TO_DATE('" . $apontMecan->dthrFinalApontMecan . "','DD/MM/YYYY HH24:MI') "
                . " , DTHR_TRANS_FINAL = SYSDATE "
                . " WHERE "
                . " ID_CEL = " . $apontMecan->idApontMecan
                . " AND "
                . " BOLETIM_ID = " . $idBol;

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

}
