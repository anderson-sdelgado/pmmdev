<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
require_once('../model/dao/AjusteDataHoraDAO.class.php');
/**
 * Description of CabecPneuDAO
 *
 * @author anderson
 */
class CabecPneuDAO extends Conn {
    //put your code here
    
    public function verifCabecPneu($idApont, $cabPneu, $base) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PMP_BOLETIM "
                . " WHERE "
                . " APONTAMENTO_ID = " . $idApont
                . " AND "
                . " FUNC_MATRIC = " . $cabPneu->funcCabecPneu
                . " AND "
                . " EQUIP_ID = " . $cabPneu->equipCabecPneu
                . " AND "
                . " DTHR_CEL = TO_DATE('" . $cabPneu->dthrCabecPneu . "','DD/MM/YYYY HH24:MI') ";
        
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

    public function idCabecPneu($idApont, $cabPneu, $base) {

        $select = " SELECT "
                . " ID AS IDBOLPNEU "
                . " FROM "
                . " PMP_BOLETIM "
                . " WHERE "
                . " APONTAMENTO_ID = " . $idApont
                . " AND "
                . " FUNC_MATRIC = " . $cabPneu->funcCabecPneu
                . " AND "
                . " EQUIP_ID = " . $cabPneu->equipCabecPneu
                . " AND "
                . " DTHR_CEL = TO_DATE('" . $cabPneu->dthrCabecPneu . "','DD/MM/YYYY HH24:MI') ";
        
        $this->Conn = parent::getConn($base);
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();
        foreach ($result as $item) {
            $id = $item['IDBOLPNEU'];
        }
        return $id;
    }

    public function insCabecPneu($idApont, $cabPneu, $tipo, $base) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        $sql = "INSERT INTO PMP_BOLETIM ("
                . " APONTAMENTO_ID "
                . " , FUNC_MATRIC "
                . " , EQUIP_ID "
                . " , DTHR "
                . " , DTHR_CEL "
                . " , DTHR_TRANS "
                . " , TIPO_APLIC "
                . " ) "
                . " VALUES ("
                . " " . $idApont
                . " , " . $cabPneu->funcCabecPneu
                . " , " . $cabPneu->equipCabecPneu
                . " , " . $ajusteDataHoraDAO->dataHoraGMT($cabPneu->dthrCabecPneu, $base)
                . " , TO_DATE('" . $cabPneu->dthrCabecPneu . "','DD/MM/YYYY HH24:MI') "
                . " , SYSDATE "
                . " , " . $tipo
                . " )";
        $this->Conn = parent::getConn($base);
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();

    }
    
}
