<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'Conn.class.php';

/**
 * Description of InsApontamentoMMDAO
 *
 * @author anderson
 */
class InserirApontDAO extends Conn {
    //put your code here

    /** @var PDO */
    private $Conn;

    public function salvarDados($dadosAponta, $dadosImplemento, $dadosApontaAplicFert) {

        $this->Conn = parent::getConn();

        foreach ($dadosAponta as $apont) {

            $select = " SELECT "
                    . " COUNT(ID) AS QTDE "
                    . " FROM "
                    . " PMM_APONTAMENTO "
                    . " WHERE "
                    . " DTHR_CEL = TO_DATE('" . $apont->dthrAponta . "','DD/MM/YYYY HH24:MI') "
                    . " AND "
                    . " BOLETIM_ID = " . $apont->idExtBolAponta . " ";

            $this->Read = $this->Conn->prepare($select);
            $this->Read->setFetchMode(PDO::FETCH_ASSOC);
            $this->Read->execute();
            $res1 = $this->Read->fetchAll();

            foreach ($res1 as $item1) {
                $v = $item1['QTDE'];
            }

            if ($v == 0) {
               
                if ($apont->transbordoAponta == 0) {
                    $apont->transbordoAponta = "null";
                }

                if ($apont->paradaAponta == 0) {
                    $apont->paradaAponta = "null";
                }

//                $this->Create = $this->Conn->prepare("begin "
//                        . " pkb_pmm_apontamento({$apont->idExtBolAponta}, {$apont->osAponta}"
//                        . " , {$apont->atividadeAponta}, {$apont->paradaAponta} "
//                        . " , {$apont->transbordoAponta}, '{$apont->dthrAponta}'); "
//                        . " end;");
//                $this->Create->execute();

                $sql = "INSERT INTO PMM_APONTAMENTO ("
                        . " BOLETIM_ID "
                        . " , OS_NRO "
                        . " , ATIVAGR_ID "
                        . " , MOTPARADA_ID "
                        . " , DTHR_CEL "
                        . " , DTHR_TRANS "
                        . " , NRO_EQUIP_TRANSB "
                        . " ) "
                        . " VALUES ("
                        . " " . $apont->idExtBolAponta
                        . " , " . $apont->osAponta
                        . " , " . $apont->atividadeAponta
                        . " , " . $apont->paradaAponta
                        . " , TO_DATE('" . $apont->dthrAponta . "','DD/MM/YYYY HH24:MI')"
                        . " , SYSDATE "
                        . " , " . $apont->transbordoAponta
                        . " )";

                $this->Create = $this->Conn->prepare($sql);
                $this->Create->execute();
                
                $sql = "UPDATE PMM_APONTAMENTO_LOGTRAC "
                        . " SET DTAFIM = TO_DATE('" . $apont->dthrAponta . "','DD/MM/YYYY HH24:MI') "
                        . " WHERE ID = "
                        . " NVL(( "
                        . " SELECT MAX(A1.ID) "
                        . " FROM PMM_APONTAMENTO_LOGTRAC A1"
                        . " , EQUIP E1"
                        . " , PMM_BOLETIM B1 "
                        . " WHERE B1.ID = " . $apont->idExtBolAponta
                        . " AND A1.CDGEQUIPAMENTO = E1.NRO_EQUIP "
                        . " AND E1.EQUIP_ID = B1.EQUIP_ID), 0)";

                $this->Create = $this->Conn->prepare($sql);
                $this->Create->execute();

                if ($apont->paradaAponta != "null") {

                    $select = " SELECT "
                            . " COUNT(A.ID) AS QTDE "
                            . " FROM "
                            . " PMM_APONTAMENTO_LOGTRAC A "
                            . " WHERE "
                            . " A.ID = (SELECT MAX(A1.ID) FROM PMM_APONTAMENTO A1 WHERE A1.MOTPARADA_ID IS NOT NULL)";

                    $this->Read = $this->Conn->prepare($select);
                    $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                    $this->Read->execute();
                    $res2 = $this->Read->fetchAll();

                    foreach ($res2 as $item2) {
                        $v = $item2['QTDE'];
                    }

                    if ($v == 0) {

                        $sql = "INSERT INTO PMM_APONTAMENTO_LOGTRAC "
                                . " (ID, CDGEQUIPAMENTO, DTAINICIO, CDGMOTIVOPARADA, CDGOM, CDGFUNCIONARIO) "
                                . " SELECT "
                                . " A.ID "
                                . " , E.NRO_EQUIP AS CDGEQUIPAMENTO "
                                . " , A.DTHR AS DTAINICIO "
                                . " , P.CD AS CDGMOTIVOPARADA "
                                . " , A.OS_NRO AS CDGOM "
                                . ", B.FUNC_MATRIC AS CDGFUNCIONARIO "
                                . " FROM "
                                . " PMM_BOLETIM B "
                                . " , PMM_APONTAMENTO A "
                                . " , EQUIP E "
                                . " , MOTIVO_PARADA P "
                                . " WHERE "
                                . " B.ID = " . $apont->idExtBolAponta
                                . " AND "
                                . " B.ID = A.BOLETIM_ID "
                                . " AND "
                                . " B.EQUIP_ID = E.EQUIP_ID "
                                . " AND A.MOTPARADA_ID = P.MOTPARADA_ID "
                                . " AND A.ID = (SELECT MAX(A1.ID) FROM PMM_APONTAMENTO A1 WHERE A1.MOTPARADA_ID IS NOT NULL)";

                        $this->Create = $this->Conn->prepare($sql);
                        $this->Create->execute();
                    }
                }

                $select = " SELECT "
                        . " ID AS ID "
                        . " FROM "
                        . " PMM_APONTAMENTO "
                        . " WHERE "
                        . " DTHR_CEL = TO_DATE('" . $apont->dthrAponta . "','DD/MM/YYYY HH24:MI') "
                        . " AND "
                        . " BOLETIM_ID = " . $apont->idExtBolAponta . " ";

                $this->Read = $this->Conn->prepare($select);
                $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                $this->Read->execute();
                $res3 = $this->Read->fetchAll();

                foreach ($res3 as $item3) {
                    $idApont = $item3['ID'];
                }

                foreach ($dadosImplemento as $imp) {

                    if ($apont->idAponta == $imp->idApontImplemento) {

                        if ($imp->codEquipImplemento != 0) {

                            $select = " SELECT "
                                    . " COUNT(*) AS QTDE "
                                    . " FROM "
                                    . " PMM_IMPLEMENTO "
                                    . " WHERE "
                                    . " APONTAMENTO_ID = " . $idApont
                                    . " AND "
                                    . " NRO_EQUIP = " . $imp->codEquipImplemento
                                    . " AND "
                                    . " POS_EQUIP = " . $imp->posImplemento
                                    . " AND "
                                    . " DTHR_CEL = TO_DATE('" . $imp->dthrImplemento . "','DD/MM/YYYY HH24:MI') "
                                    ;

                            $this->Read = $this->Conn->prepare($select);
                            $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                            $this->Read->execute();
                            $res4 = $this->Read->fetchAll();

                            foreach ($res4 as $item4) {
                                $v = $item4['QTDE'];
                            }

                            if ($v == 0) {

                                $sql = "INSERT INTO PMM_IMPLEMENTO ("
                                        . " APONTAMENTO_ID "
                                        . " , NRO_EQUIP "
                                        . " , POS_EQUIP "
                                        . " , DTHR_CEL "
                                        . " , DTHR_TRANS "
                                        . " ) "
                                        . " VALUES ("
                                        . " " . $idApont
                                        . " , " . $imp->codEquipImplemento
                                        . " , " . $imp->posImplemento
                                        . " , TO_DATE('" . $imp->dthrImplemento . "','DD/MM/YYYY HH24:MI') "
                                        . " , SYSDATE "
                                        . " )";

                                $this->Create = $this->Conn->prepare($sql);
                                $this->Create->execute();
                            }
                        }
                    }
                }
            } else {

                $select = " SELECT "
                        . " ID AS ID "
                        . " FROM "
                        . " PMM_APONTAMENTO "
                        . " WHERE "
                        . " DTHR_CEL = TO_DATE('" . $apont->dthrAponta . "','DD/MM/YYYY HH24:MI') "
                        . " AND "
                        . " BOLETIM_ID = " . $apont->idExtBolAponta . " ";

                $this->Read = $this->Conn->prepare($select);
                $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                $this->Read->execute();
                $res5 = $this->Read->fetchAll();

                foreach ($res5 as $item5) {
                    $idApont = $item5['ID'];
                }

                foreach ($dadosImplemento as $imp) {

                    if ($apont->idAponta == $imp->idApontImplemento) {

                        if ($imp->codEquipImplemento != 0) {

                            $select = " SELECT "
                                    . " COUNT(*) AS QTDE "
                                    . " FROM "
                                    . " PMM_IMPLEMENTO "
                                    . " WHERE "
                                    . " APONTAMENTO_ID = " . $idApont
                                    . " AND "
                                    . " NRO_EQUIP = " . $imp->codEquipImplemento
                                    . " AND "
                                    . " POS_EQUIP = " . $imp->posImplemento
                                    . " AND "
                                    . " DTHR_CEL = TO_DATE('" . $imp->dthrImplemento . "','DD/MM/YYYY HH24:MI') ";

                            $this->Read = $this->Conn->prepare($select);
                            $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                            $this->Read->execute();
                            $res6 = $this->Read->fetchAll();

                            foreach ($res6 as $item6) {
                                $v = $item6['QTDE'];
                            }

                            if ($v == 0) {

                                $sql = "INSERT INTO PMM_IMPLEMENTO ("
                                        . " APONTAMENTO_ID "
                                        . " , NRO_EQUIP "
                                        . " , POS_EQUIP "
                                        . " , DTHR_CEL "
                                        . " , DTHR_TRANS "
                                        . " ) "
                                        . " VALUES ("
                                        . " " . $idApont
                                        . " , " . $imp->codEquipImplemento
                                        . " , " . $imp->posImplemento
                                        . " , TO_DATE('" . $imp->dthrImplemento . "','DD/MM/YYYY HH24:MI') "
                                        . " , SYSDATE "
                                        . " )";

                                $this->Create = $this->Conn->prepare($sql);
                                $this->Create->execute();
                            }
                        }
                    }
                }
            }
        }

        foreach ($dadosApontaAplicFert as $apontaAplicFert) {

            if ($apontaAplicFert->paradaApontaAplicFert == 0) {
                $apontaAplicFert->paradaApontaAplicFert = "null";
            }

            $insert = "INSERT INTO PMM_FERT_APLICACAO ("
                    . " BOLETIM_ID "
                    . " , EQUIP_ID "
                    . " , OS_NRO "
                    . " , ATIVAGR_ID "
                    . " , MOTPARADA_ID "
                    . " , DTHR "
                    . " , DTHR_TRANS "
                    . " , PRESSAO "
                    . " , VELOCIDADE "
                    . " , BOCAL "
                    . " , RAIO "
                    . " ) "
                    . " VALUES ("
                    . " " . $apontaAplicFert->idExtBolApontaAplicFert
                    . " , " . $apontaAplicFert->equipApontaAplicFert
                    . " , " . $apontaAplicFert->osApontaAplicFert
                    . " , " . $apontaAplicFert->ativApontaAplicFert
                    . " , " . $apontaAplicFert->paradaApontaAplicFert
                    . " , TO_DATE('" . $apontaAplicFert->dthrApontaAplicFert . "','DD/MM/YYYY HH24:MI') "
                    . " , SYSDATE "
                    . " , " . $apontaAplicFert->pressaoApontaAplicFert
                    . " , " . $apontaAplicFert->velocApontaAplicFert
                    . " , " . $apontaAplicFert->bocalApontaAplicFert
                    . " , " . $apontaAplicFert->raioApontaAplicFert
                    . " )";

            $this->Create = $this->Conn->prepare($insert);
            $this->Create->execute();
        }
    }

}
