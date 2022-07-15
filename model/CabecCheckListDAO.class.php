<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
/**
 * Description of CabecChecklist
 *
 * @author anderson
 */
class CabecCheckListDAO extends Conn {

    public function verifCabecCheckList($cab) {

        $select = " SELECT "
                    . " COUNT(*) AS QTDE "
                . " FROM "
                    . " BOLETIM_CHECK "
                . " WHERE "
                    . " DTHR_CELULAR = TO_DATE('" . $cab->dtCabCL . "','DD/MM/YYYY HH24:MI') "
                    . " AND "
                    . " EQUIP_NRO = " . $cab->equipCabCL;

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

    public function idCabecCheckList($cab) {

        $select = " SELECT "
                    . " ID_BOLETIM AS ID "
                . " FROM "
                    . " BOLETIM_CHECK "
                . " WHERE "
                    . " DTHR_CELULAR  = TO_DATE('" . $cab->dtCabCL . "','DD/MM/YYYY HH24:MI') "
                    . " AND "
                    . " EQUIP_NRO = " . $cab->equipCabCL;

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

    public function insCabecCheckList($cab) {

		$turno = 'NULL';

		if (isset($cab->turnoCabCL)) {
			$select = " SELECT "
						. " NRO_TURNO "
					. " FROM "
						. " USINAS.TURNO_TRAB "
					. " WHERE "
						. " TURNOTRAB_ID = " . $cab->turnoCabCL;

			$this->Conn = parent::getConn();
			$this->Read = $this->Conn->prepare($select);
			$this->Read->setFetchMode(PDO::FETCH_ASSOC);
			$this->Read->execute();
			$result = $this->Read->fetchAll();

			foreach ($result as $item) {
				$turno = $item['NRO_TURNO'];
			}
		}
		
		if (!isset($cab->funcCabCL)) {
			$cab->funcCabCL = 'NULL';
		}

        $sql = " INSERT INTO BOLETIM_CHECK ( "
                . " EQUIP_NRO "
                . " , FUNC_CD "
                . " , DT "
                . " , DTHR_CELULAR "
                . " , NRO_TURNO "
                . " ) "
                . " VALUES ( "
                . " " . $cab->equipCabCL . " "
                . " , " . $cab->funcCabCL . ""
                . " , TO_DATE('" . $cab->dtCabCL . "','DD/MM/YYYY HH24:MI')"
                . " , TO_DATE('" . $cab->dtCabCL . "','DD/MM/YYYY HH24:MI')"
                . " , " . $turno . ")";

        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();

    }

}
