<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'Conn.class.php';

/**
 * Description of ApontaCheckList
 *
 * @author anderson
 */
class ApontCheckListDAO extends Conn {
    //put your code here

    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function salvarDados($dadosCab, $dadosItem) {

        foreach ($dadosCab as $d) {

            $turno = 'null';

            $this->Conn = parent::getConn();

            $select = " SELECT "
                    . " COUNT(*) AS QTDE "
                    . " FROM "
                    . " BOLETIM_CHECK "
                    . " WHERE "
                    . " DT = TO_DATE('" . $d->dtCabecCheckList . "','DD/MM/YYYY HH24:MI') "
                    . " AND "
                    . " EQUIP_NRO = " . $d->equipCabecCheckList . " ";

            $this->Read = $this->Conn->prepare($select);
            $this->Read->setFetchMode(PDO::FETCH_ASSOC);
            $this->Read->execute();
            $result = $this->Read->fetchAll();

            foreach ($result as $item) {
                $v = $item['QTDE'];
            }

            if ($v == 0) {

                $select = " SELECT "
                        . " NRO_TURNO "
                        . " FROM "
                        . " USINAS.V_SIMOVA_TURNO_EQUIP_NEW "
                        . " WHERE TURNOTRAB_ID = " . $d->turnoCabecCheckList;

                $this->Read = $this->Conn->prepare($select);
                $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                $this->Read->execute();
                $result = $this->Read->fetchAll();

                foreach ($result as $item) {
                    $turno = $item['NRO_TURNO'];
                }

                $sql = " INSERT INTO BOLETIM_CHECK ( "
                        . " EQUIP_NRO "
                        . " , FUNC_CD "
                        . " , DT "
                        . " , NRO_TURNO "
                        . " ) "
                        . " VALUES ( "
                        . " " . $d->equipCabecCheckList . " "
                        . " , " . $d->funcCabecCheckList . ""
                        . " , TO_DATE('" . $d->dtCabecCheckList . "','DD/MM/YYYY HH24:MI') "
                        . " , " . $turno . ")";

                $this->Create = $this->Conn->prepare($sql);
                $this->Create->execute();

                if ($d->dtAtualCheckList != "0") {

                    $sql = " UPDATE USINAS.ATUALIZA_CHECKLIST_MOBILE  "
                            . " SET DT_MOBILE = TO_DATE('" . $d->dtAtualCheckList . "','DD/MM/YYYY HH24:MI') "
                            . " WHERE EQUIP_NRO = " . $d->equipCabecCheckList;

                    $this->Create = $this->Conn->prepare($sql);
                    $this->Create->execute();
                }

                $select = " SELECT "
                        . " ID_BOLETIM AS ID "
                        . " FROM "
                        . " BOLETIM_CHECK "
                        . " WHERE "
                        . " DT = TO_DATE('" . $d->dtCabecCheckList . "','DD/MM/YYYY HH24:MI') "
                        . " AND "
                        . " EQUIP_NRO = " . $d->equipCabecCheckList . " ";

                $this->Read = $this->Conn->prepare($select);
                $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                $this->Read->execute();
                $result = $this->Read->fetchAll();

                foreach ($result as $item) {
                    $qtdeCab = $item['ID'];
                }

                foreach ($dadosItem as $i) {

                    if ($d->idCabecCheckList == $i->idCabecItemCheckList) {

                        $grupo = '';
                        $questao = '';

                        $select = " select "
                                . " vipc.itmanprev_id as id, "
                                . " CARACTER(vipc.proc_oper) as questao, "
                                . " CARACTER(vcc.descr) as grupo "
                                . " from "
                                . " v_item_plano_check vipc "
                                . " , v_componente_check vcc "
                                . " where "
                                . " vipc.itmanprev_id = " . $i->idItItemCheckList . " "
                                . " and "
                                . " vipc.componente_id = vcc.componente_id ";

                        $this->Read = $this->Conn->prepare($select);
                        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                        $this->Read->execute();
                        $result = $this->Read->fetchAll();


                        foreach ($result as $inf) {
                            $questao = $inf['QUESTAO'];
                            $grupo = $inf['GRUPO'];
                        }

                        if (!isset($i->opcaoItemCheckList) || empty($i->opcaoItemCheckList)) {
                            $i->opcaoItemCheckList = 0;
                        }

                        $select = " SELECT "
                                . " COUNT(*) AS QTDE "
                                . " FROM "
                                . " ITEM_BOLETIM_CHECK "
                                . " WHERE "
                                . " ID_BOLETIM = " . $qtdeCab . " "
                                . " AND "
                                . " ITMANPREV_ID = " . $i->idItItemCheckList . " ";

                        $this->Read = $this->Conn->prepare($select);
                        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                        $this->Read->execute();
                        $result = $this->Read->fetchAll();

                        foreach ($result as $item) {
                            $v = $item['QTDE'];
                        }

                        if ($v == 0) {

                            $sql = " INSERT INTO ITEM_BOLETIM_CHECK ( "
                                    . " ID_BOLETIM "
                                    . " , GRUPO "
                                    . " , QUESTAO "
                                    . " , RESP_CD "
                                    . " , ITMANPREV_ID "
                                    . " ) "
                                    . " VALUES ( "
                                    . " " . $qtdeCab . " "
                                    . " , '" . $grupo . "' "
                                    . " , '" . $questao . "' "
                                    . " , " . $i->opcaoItemCheckList . " "
                                    . " , " . $i->idItItemCheckList . ")";

                            $this->Create = $this->Conn->prepare($sql);
                            $this->Create->execute();
                        }
                    }
                }
            } else {

                $select = " SELECT "
                        . " ID_BOLETIM AS ID "
                        . " FROM "
                        . " BOLETIM_CHECK "
                        . " WHERE "
                        . " DT = TO_DATE('" . $d->dtCabecCheckList . "','DD/MM/YYYY HH24:MI') "
                        . " AND "
                        . " EQUIP_NRO = " . $d->equipCabecCheckList . " ";

                $this->Read = $this->Conn->prepare($select);
                $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                $this->Read->execute();
                $result = $this->Read->fetchAll();

                foreach ($result as $item) {
                    $qtdeCab = $item['ID'];
                }

                foreach ($dadosItem as $i) {

                    if ($d->idCabecCheckList == $i->idCabecItemCheckList) {

                        $grupo = '';
                        $questao = '';

                        $select = " select "
                                . " vipc.itmanprev_id as id, "
                                . " CARACTER(vipc.proc_oper) as questao, "
                                . " CARACTER(vcc.descr) as grupo "
                                . " from "
                                . " v_item_plano_check vipc "
                                . " , v_componente_check vcc "
                                . " where "
                                . " vipc.itmanprev_id = " . $i->idItItemCheckList . " "
                                . " and "
                                . " vipc.componente_id = vcc.componente_id ";

                        $this->Read = $this->Conn->prepare($select);
                        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                        $this->Read->execute();
                        $result = $this->Read->fetchAll();


                        foreach ($result as $inf) {
                            $questao = $inf['QUESTAO'];
                            $grupo = $inf['GRUPO'];
                        }

                        if (!isset($i->opcaoItemCheckList) || empty($i->opcaoItemCheckList)) {
                            $i->opcaoItemCheckList = 0;
                        }

                        $select = " SELECT "
                                . " COUNT(*) AS QTDE "
                                . " FROM "
                                . " ITEM_BOLETIM_CHECK "
                                . " WHERE "
                                . " ID_BOLETIM = " . $qtdeCab . " "
                                . " AND "
                                . " ITMANPREV_ID = " . $i->idItItemCheckList . " ";

                        $this->Read = $this->Conn->prepare($select);
                        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                        $this->Read->execute();
                        $result = $this->Read->fetchAll();

                        foreach ($result as $item) {
                            $v = $item['QTDE'];
                        }

                        if ($v == 0) {

                            $sql = " INSERT INTO ITEM_BOLETIM_CHECK ( "
                                    . " ID_BOLETIM "
                                    . " , GRUPO "
                                    . " , QUESTAO "
                                    . " , RESP_CD "
                                    . " , ITMANPREV_ID "
                                    . " ) "
                                    . " VALUES ( "
                                    . " " . $qtdeCab . " "
                                    . " , '" . $grupo . "' "
                                    . " , '" . $questao . "' "
                                    . " , " . $i->opcaoItemCheckList . " "
                                    . " , " . $i->idItItemCheckList . ")";

                            $this->Create = $this->Conn->prepare($sql);
                            $this->Create->execute();
                        }
                    }
                }
            }
        }
    }

}
