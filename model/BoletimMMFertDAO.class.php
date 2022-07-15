<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
/**
 * Description of BoletimMMFert
 *
 * @author anderson
 */
class BoletimMMFertDAO extends Conn {

    public function verifBoletimMM($bol) {

		$select = " SELECT "
				. " COUNT(*) AS QTDE "
				. " FROM "
				. " PMM_BOLETIM "
				. " WHERE "
				. " DTHR_INICIAL_CEL = TO_DATE('" . $bol->dthrInicialBolMMFert . "','DD/MM/YYYY HH24:MI')"
				. " AND "
				. " EQUIP_ID = " . $bol->idEquipBolMMFert . " ";

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
                . " DTHR_INICIAL_CEL = TO_DATE('" . $bol->dthrInicialBolMMFert . "','DD/MM/YYYY HH24:MI')"
                . " AND "
                . " EQUIP_ID = " . $bol->idEquipBolMMFert . " ";

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

        if ($bol->hodometroInicialBolMMFert > 9999999) {
            $bol->hodometroInicialBolMMFert = 0;
        }
        
        if ($bol->osBolMMFert == 0) {
            $bol->osBolMMFert = 'NULL';
        }
        
        if ($bol->ativPrincBolMMFert == 0) {
            $bol->ativPrincBolMMFert = 'NULL';
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
                . " " . $bol->matricFuncBolMMFert
                . " , " . $bol->idEquipBolMMFert
                . " , " . $bol->idTurnoBolMMFert
                . " , " . $bol->hodometroInicialBolMMFert
                . " , " . $bol->osBolMMFert
                . " , " . $bol->ativPrincBolMMFert
                . " , TO_DATE('" . $bol->dthrInicialBolMMFert . "','DD/MM/YYYY HH24:MI')"
                . " , TO_DATE('" . $bol->dthrInicialBolMMFert . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " , 1 "
                . " , " . $bol->statusConBolMMFert
                . " )";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    public function insBoletimMMFechado($bol) {

        if ($bol->hodometroInicialBolMMFert > 9999999) {
            $bol->hodometroInicialBolMMFert = 0;
        }

        if ($bol->hodometroFinalBolMMFert > 9999999) {
            $bol->hodometroFinalBolMMFert = 0;
        }
        
        if ($bol->osBolMMFert == 0) {
            $bol->osBolMMFert = "null";
        }
        
        if ($bol->ativPrincBolMMFert == 0) {
            $bol->ativPrincBolMMFert = "null";
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
                . " " . $bol->matricFuncBolMMFert
                . " , " . $bol->idEquipBolMMFert
                . " , " . $bol->idTurnoBolMMFert
                . " , " . $bol->hodometroInicialBolMMFert
                . " , " . $bol->hodometroFinalBolMMFert
                . " , " . $bol->osBolMMFert
                . " , " . $bol->ativPrincBolMMFert
                . " , TO_DATE('" . $bol->dthrInicialBolMMFert . "','DD/MM/YYYY HH24:MI')"
                . " , TO_DATE('" . $bol->dthrInicialBolMMFert . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " , TO_DATE('" . $bol->dthrFinalBolMMFert . "','DD/MM/YYYY HH24:MI')"
                . " , TO_DATE('" . $bol->dthrFinalBolMMFert . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " , 2 "
                . " , " . $bol->statusConBolMMFert
                . " )";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    public function updateBoletimMMFechado($idBol, $bol) {

        if ($bol->hodometroFinalBolMMFert > 9999999) {
            $bol->hodometroFinalBolMMFert = 0;
        }

        $sql = "UPDATE PMM_BOLETIM "
                . " SET "
                . " HOD_HOR_FINAL = " . $bol->hodometroFinalBolMMFert
                . " , STATUS = " . $bol->statusBolMMFert
                . " , DTHR_FINAL = TO_DATE('" . $bol->dthrFinalBolMMFert . "','DD/MM/YYYY HH24:MI')"
                . " , DTHR_FINAL_CEL = TO_DATE('" . $bol->dthrFinalBolMMFert . "','DD/MM/YYYY HH24:MI')"
                . " , DTHR_TRANS_FINAL = SYSDATE "
                . " WHERE "
                . " ID = " . $idBol;

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }
    
    public function updateBoletimMMOSAtiv($idBol, $apont) {

        $sql = "UPDATE PMM_BOLETIM "
                . " SET "
                . " OS_NRO = " . $apont->osApontMMFert
                . " , ATIVAGR_PRINC_ID = " . $apont->ativApontMMFert
                . " WHERE "
                . " ID = " . $idBol
                . " AND "
                . " OS_NRO IS NULL ";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    public function verifBoletimFert($bol) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PMM_BOLETIM_FERT "
                . " WHERE "
                . " DTHR_INICIAL_CEL = TO_DATE('" . $bol->dthrInicialBolMMFert . "','DD/MM/YYYY HH24:MI')"
                . " AND "
                . " EQUIP_ID = " . $bol->idEquipBolMMFert . " ";

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

    public function idBoletimFert($bol) {

        $select = " SELECT "
                . " ID AS ID "
                . " FROM "
                . " PMM_BOLETIM_FERT "
                . " WHERE "
                . " DTHR_INICIAL_CEL = TO_DATE('" . $bol->dthrInicialBolMMFert . "','DD/MM/YYYY HH24:MI')"
                . " AND "
                . " EQUIP_ID = " . $bol->idEquipBolMMFert . " ";

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

    public function insBoletimFertAberto($bol) {

        if ($bol->hodometroInicialBolMMFert > 9999999) {
            $bol->hodometroInicialBolMMFert = 0;
        }
        
        if ($bol->idEquipBolMMFert == $bol->idEquipBombaBolMMFert) {
            $motoBomba = null;
        }
        else{
            $motoBomba = $bol->idEquipBombaBolMMFert;
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
                . " " . $bol->matricFuncBolMMFert
                . " , " . $bol->idEquipBolMMFert
                . " , " . $motoBomba
                . " , " . $bol->idTurnoBolMMFert
                . " , " . $bol->hodometroInicialBolMMFert
                . " , " . $bol->osBolMMFert
                . " , " . $bol->ativPrincBolMMFert
                . " , TO_DATE('" . $bol->dthrInicialBolMMFert . "','DD/MM/YYYY HH24:MI')"
                . " , TO_DATE('" . $bol->dthrInicialBolMMFert . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " , 1 "
                . " , " . $bol->statusConBolMMFert
                . " )";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    public function insBoletimFertFechado($bol) {

        if ($bol->hodometroInicialBolMMFert > 9999999) {
            $bol->hodometroInicialBolMMFert = 0;
        }

        if ($bol->hodometroFinalBolMMFert > 9999999) {
            $bol->hodometroFinalBolMMFert = 0;
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
                . " " . $bol->matricFuncBolMMFert
                . " , " . $bol->idEquipBolMMFert
                . " , " . $bol->idEquipBombaBolMMFert
                . " , " . $bol->idTurnoBolMMFert
                . " , " . $bol->hodometroInicialBolMMFert
                . " , " . $bol->hodometroFinalBolMMFert
                . " , " . $bol->osBolMMFert
                . " , " . $bol->ativPrincBolMMFert
                . " , TO_DATE('" . $bol->dthrInicialBolMMFert . "','DD/MM/YYYY HH24:MI')"
                . " , TO_DATE('" . $bol->dthrInicialBolMMFert . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " , TO_DATE('" . $bol->dthrFinalBolMMFert . "','DD/MM/YYYY HH24:MI')"
                . " , TO_DATE('" . $bol->dthrFinalBolMMFert . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " , 2 "
                . " , " . $bol->statusConBolMMFert
                . " )";

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    public function updateBoletimFertFechado($idBol, $bol) {

        if ($bol->hodometroFinalBolMMFert > 9999999) {
            $bol->hodometroFinalBolMMFert = 0;
        }

        $sql = "UPDATE PMM_BOLETIM_FERT "
                . " SET "
                . " HOD_HOR_FINAL = " . $bol->hodometroFinalBolMMFert
                . " , STATUS = " . $bol->statusBolMMFert
                . " , DTHR_FINAL = TO_DATE('" . $bol->dthrFinalBolMMFert . "','DD/MM/YYYY HH24:MI')"
                . " , DTHR_FINAL_CEL = TO_DATE('" . $bol->dthrFinalBolMMFert . "','DD/MM/YYYY HH24:MI')"
                . " , DTHR_TRANS_FINAL = SYSDATE "
                . " WHERE "
                . " ID = " . $idBol;

        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }
    
}
