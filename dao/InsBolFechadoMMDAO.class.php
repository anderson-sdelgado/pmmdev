<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'Conn.class.php';

/**
 * Description of InsBolFechadoMMDAO
 *
 * @author anderson
 */
class InsBolFechadoMMDAO extends Conn {
    //put your code here

    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function salvarDados($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosRendimento, $dadosApontaAplicFert, $dadosRecolMang) {

        $this->Conn = parent::getConn();

        foreach ($dadosBoletim as $bol) {

            $select = " SELECT "
                    . " COUNT(*) AS QTDE "
                    . " FROM "
                    . " PMM_BOLETIM "
                    . " WHERE "
                    . " DTHR_INICIAL_CEL = TO_DATE('" . $bol->dthrInicioBoletim . "','DD/MM/YYYY HH24:MI')"
                    . " AND "
                    . " EQUIP_ID = " . $bol->codEquipBoletim . " ";

            $this->Read = $this->Conn->prepare($select);
            $this->Read->setFetchMode(PDO::FETCH_ASSOC);
            $this->Read->execute();
            $res1 = $this->Read->fetchAll();

            foreach ($res1 as $item1) {
                $v = $item1['QTDE'];
            }

            if ($v == 0) {

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
                        . " , DTHR_INICIAL_CEL "
                        . " , DTHR_TRANS_INICIAL "
                        . " , DTHR_FINAL_CEL "
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

                $this->Create = $this->Conn->prepare($sql);
                $this->Create->execute();

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
                $res2 = $this->Read->fetchAll();

                foreach ($res2 as $item2) {
                    $idBoletim = $item2['ID'];
                }

                foreach ($dadosAponta as $apont) {

                    if ($bol->idBoletim == $apont->idBolAponta) {

                        $select = " SELECT "
                                . " COUNT(*) AS QTDE "
                                . " FROM "
                                . " PMM_APONTAMENTO "
                                . " WHERE "
                                . " DTHR_CEL = TO_DATE('" . $apont->dthrAponta . "','DD/MM/YYYY HH24:MI')"
                                . " AND "
                                . " BOLETIM_ID = " . $idBoletim . " ";

                        $this->Read = $this->Conn->prepare($select);
                        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                        $this->Read->execute();
                        $res3 = $this->Read->fetchAll();

                        foreach ($res3 as $item3) {
                            $v = $item3['QTDE'];
                        }

                        if ($apont->transbordoAponta == 0) {
                            $apont->transbordoAponta = "null";
                        }

                        if ($apont->paradaAponta == 0) {
                            $apont->paradaAponta = "null";
                        }

                        if ($v == 0) {

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
                                    . " SET DTAFIM = TO_DATE('" . $apont->dthrAponta . "','DD/MM/YYYY HH24:MI')"
                                    . " WHERE ID = "
                                    . " NVL(( "
                                    . " SELECT MAX(A1.ID) "
                                    . " FROM PMM_APONTAMENTO_LOGTRAC A1"
                                    . " , EQUIP E1"
                                    . " , PMM_BOLETIM B1 "
                                    . " WHERE B1.ID = " . $idBoletim
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
                                $res4 = $this->Read->fetchAll();

                                foreach ($res4 as $item4) {
                                    $v = $item4['QTDE'];
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
                                            . " B.ID = " . $idBoletim
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
                                    . " DTHR_CEL = TO_DATE('" . $apont->dthrAponta . "','DD/MM/YYYY HH24:MI')"
                                    . " AND "
                                    . " BOLETIM_ID = " . $idBoletim . " ";

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
                        } else {

                            $select = " SELECT "
                                    . " ID AS ID "
                                    . " FROM "
                                    . " PMM_APONTAMENTO "
                                    . " WHERE "
                                    . " DTHR_CEL = TO_DATE('" . $apont->dthrAponta . "','DD/MM/YYYY HH24:MI')"
                                    . " AND "
                                    . " BOLETIM_ID = " . $idBoletim . " ";

                            $this->Read = $this->Conn->prepare($select);
                            $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                            $this->Read->execute();
                            $res7 = $this->Read->fetchAll();

                            foreach ($res7 as $item7) {
                                $idApont = $item7['ID'];
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
                                        $res8 = $this->Read->fetchAll();

                                        foreach ($res8 as $item8) {
                                            $v = $item8['QTDE'];
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
                }

                foreach ($dadosRendimento as $rend) {

                    if ($bol->idBoletim == $rend->idBolRendimento) {

                        $select = " SELECT "
                                . " COUNT(*) AS QTDE "
                                . " FROM "
                                . " PMM_RENDIMENTO "
                                . " WHERE "
                                . " OS_NRO = " . $rend->nroOSRendimento
                                . " AND "
                                . " DTHR_CEL = TO_DATE('" . $rend->dthrRendimento . "','DD/MM/YYYY HH24:MI') "
                                . " AND "
                                . " BOLETIM_ID = " . $idBoletim;

                        $this->Read = $this->Conn->prepare($select);
                        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                        $this->Read->execute();
                        $res17 = $this->Read->fetchAll();

                        foreach ($res17 as $item17) {
                            $v = $item17['QTDE'];
                        }

                        if ($v == 0) {

                            $sql = "INSERT INTO PMM_RENDIMENTO ("
                                    . " BOLETIM_ID "
                                    . " , OS_NRO "
                                    . " , VL "
                                    . " , DTHR_CEL "
                                    . " , DTHR_TRANS "
                                    . " ) "
                                    . " VALUES ("
                                    . " " . $idBoletim
                                    . " , " . $rend->nroOSRendimento
                                    . " , " . $rend->valorRendimento
                                    . " , TO_DATE('" . $rend->dthrRendimento . "','DD/MM/YYYY HH24:MI') "
                                    . " , SYSDATE "
                                    . " )";

                            $this->Create = $this->Conn->prepare($sql);
                            $this->Create->execute();
                        }
                    }
                }

                foreach ($dadosApontaAplicFert as $apontaAplicFert) {

                    if ($bol->idBoletim == $apontaAplicFert->idBolApontaAplicFert) {

                        if ($apontaAplicFert->paradaApontaAplicFert == 0) {
                            $apontaAplicFert->paradaApontaAplicFert = "null";
                        }

                        $sql = "INSERT INTO PMM_FERT_APLICACAO ("
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
                                . " " . $idBoletim
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

                        $this->Create = $this->Conn->prepare($sql);
                        $this->Create->execute();
                    }
                }

                foreach ($dadosRecolMang as $recolMang) {

                    if ($bol->idBoletim == $recolMang->idBolRendMangRecol) {

                        $sql = "INSERT INTO PMM_FERT_PRODUCAO ("
                                . " BOLETIM_ID "
                                . " , OS_NRO "
                                . " , EQUIP_ID "
                                . " , MANGUEIRA_REC "
                                . " , DTHR "
                                . " , DTHR_TRANS "
                                . " ) "
                                . " VALUES ("
                                . " " . $idBoletim
                                . " , " . $recolMang->nroOSRendMangRecol
                                . " , " . $recolMang->equipRendMangRecol
                                . " , " . $recolMang->valorRendMangRecol
                                . " , TO_DATE('" . $recolMang->dthrRendMangRecol . "','DD/MM/YYYY HH24:MI') "
                                . " , SYSDATE "
                                . " )";

                        $this->Create = $this->Conn->prepare($sql);
                        $this->Create->execute();
                    }
                }
            } else {

                if ($bol->hodometroFinalBoletim > 9999999) {
                    $bol->hodometroFinalBoletim = 0;
                }

                $sql = "UPDATE PMM_BOLETIM "
                        . " SET "
                        . " HOD_HOR_FINAL = " . $bol->hodometroFinalBoletim
                        . " , STATUS = " . $bol->statusBoletim
                        . " , DTHR_FINAL_CEL = TO_DATE('" . $bol->dthrFimBoletim . "','DD/MM/YYYY HH24:MI')"
                        . " , DTHR_TRANS_FINAL = SYSDATE "
                        . " WHERE "
                        . " ID = " . $bol->idExtBoletim;

                $this->Create = $this->Conn->prepare($sql);
                $this->Create->execute();

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
                $res10 = $this->Read->fetchAll();

                foreach ($res10 as $item10) {
                    $idBol = $item10['ID'];
                }

                foreach ($dadosAponta as $apont) {

                    if ($bol->idBoletim == $apont->idBolAponta) {

                        $select = " SELECT "
                                . " COUNT(*) AS QTDE "
                                . " FROM "
                                . " PMM_APONTAMENTO "
                                . " WHERE "
                                . " DTHR_CEL = TO_DATE('" . $apont->dthrAponta . "','DD/MM/YYYY HH24:MI')"
                                . " AND "
                                . " BOLETIM_ID = " . $idBol . " ";

                        $this->Read = $this->Conn->prepare($select);
                        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                        $this->Read->execute();
                        $res11 = $this->Read->fetchAll();

                        foreach ($res11 as $item11) {
                            $v = $item11['QTDE'];
                        }

                        if ($apont->transbordoAponta == 0) {
                            $apont->transbordoAponta = "null";
                        }

                        if ($apont->paradaAponta == 0) {
                            $apont->paradaAponta = "null";
                        }

                        if ($v == 0) {

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
                                    . " " . $idBol
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
                                    . " SET DTAFIM = TO_DATE('" . $apont->dthrAponta . "','DD/MM/YYYY HH24:MI')"
                                    . " WHERE ID = "
                                    . " NVL(( "
                                    . " SELECT MAX(A1.ID) "
                                    . " FROM PMM_APONTAMENTO_LOGTRAC A1"
                                    . " , EQUIP E1"
                                    . " , PMM_BOLETIM B1 "
                                    . " WHERE B1.ID = " . $idBol
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
                                $res12 = $this->Read->fetchAll();

                                foreach ($res12 as $item12) {
                                    $v = $item12['QTDE'];
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
                                            . " B.ID = " . $idBol
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
                                    . " DTHR_CEL = TO_DATE('" . $apont->dthrAponta . "','DD/MM/YYYY HH24:MI')"
                                    . " AND "
                                    . " BOLETIM_ID = " . $idBol . " ";

                            $this->Read = $this->Conn->prepare($select);
                            $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                            $this->Read->execute();
                            $res13 = $this->Read->fetchAll();

                            foreach ($res13 as $item13) {
                                $idApont = $item13['ID'];
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
                                        $res14 = $this->Read->fetchAll();

                                        foreach ($res14 as $item14) {
                                            $v = $item14['QTDE'];
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
                                    . " DTHR_CEL = TO_DATE('" . $apont->dthrAponta . "','DD/MM/YYYY HH24:MI')"
                                    . " AND "
                                    . " BOLETIM_ID = " . $idBol . " ";

                            $this->Read = $this->Conn->prepare($select);
                            $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                            $this->Read->execute();
                            $res15 = $this->Read->fetchAll();

                            foreach ($res15 as $item15) {
                                $idApont = $item15['ID'];
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
                                        $res16 = $this->Read->fetchAll();

                                        foreach ($res16 as $item16) {
                                            $v = $item16['QTDE'];
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
                }

                foreach ($dadosRendimento as $rend) {

                    if ($bol->idBoletim == $rend->idBolRendimento) {

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

                        $this->Read = $this->Conn->prepare($select);
                        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                        $this->Read->execute();
                        $res17 = $this->Read->fetchAll();

                        foreach ($res17 as $item17) {
                            $v = $item17['QTDE'];
                        }

                        if ($v == 0) {

                            $sql = "INSERT INTO PMM_RENDIMENTO ("
                                    . " BOLETIM_ID "
                                    . " , OS_NRO "
                                    . " , VL "
                                    . " , DTHR_CEL "
                                    . " , DTHR_TRANS "
                                    . " ) "
                                    . " VALUES ("
                                    . " " . $idBol
                                    . " , " . $rend->nroOSRendimento
                                    . " , " . $rend->valorRendimento
                                    . " , TO_DATE('" . $rend->dthrRendimento . "','DD/MM/YYYY HH24:MI') "
                                    . " , SYSDATE "
                                    . " )";

                            $this->Create = $this->Conn->prepare($sql);
                            $this->Create->execute();
                        }
                    }
                }

                foreach ($dadosApontaAplicFert as $apontaAplicFert) {

                    if ($bol->idBoletim == $apontaAplicFert->idBolApontaAplicFert) {

                        if ($apontaAplicFert->paradaApontaAplicFert == 0) {
                            $apontaAplicFert->paradaApontaAplicFert = "null";
                        }

                        $sql = "INSERT INTO PMM_FERT_APLICACAO ("
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
                                . " " . $idBol
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

                        $this->Create = $this->Conn->prepare($sql);
                        $this->Create->execute();
                    }
                }

                foreach ($dadosRecolMang as $recolMang) {

                    if ($bol->idBoletim == $recolMang->idBolRendMangRecol) {

                        $sql = "INSERT INTO PMM_FERT_PRODUCAO ("
                                . " BOLETIM_ID "
                                . " , OS_NRO "
                                . " , EQUIP_ID "
                                . " , MANGUEIRA_REC "
                                . " , DTHR "
                                . " , DTHR_TRANS "
                                . " ) "
                                . " VALUES ("
                                . " " . $idBol
                                . " , " . $recolMang->nroOSRendMangRecol
                                . " , " . $recolMang->equipRendMangRecol
                                . " , " . $recolMang->valorRendMangRecol
                                . " , TO_DATE('" . $recolMang->dthrRendMangRecol . "','DD/MM/YYYY HH24:MI') "
                                . " , SYSDATE "
                                . " )";

                        $this->Create = $this->Conn->prepare($sql);
                        $this->Create->execute();
                    }
                }
            }
        }

        return 'GRAVOU-BOLFECHADO';
    }

}
