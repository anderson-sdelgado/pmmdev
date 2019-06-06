<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'Conn.class.php';
require_once 'AjusteDataHoraDAO.class.php';

/**
 * Description of InsBolAbertoMMDAO
 *
 * @author anderson
 */
class InserirBolAbertoFert2DAO extends Conn {
    //put your code here

    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function salvarDados($dadosBoletim, $dadosAponta, $dadosBolPneu, $dadosItemPneu) {

        $this->Conn = parent::getConn();

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        foreach ($dadosBoletim as $bol) {

            $select = " SELECT "
                    . " COUNT(*) AS QTDE "
                    . " FROM "
                    . " PMM_BOLETIM_FERT "
                    . " WHERE "
                    . " DTHR_INICIAL_CEL = TO_DATE('" . $bol->dthrInicioBolFert . "','DD/MM/YYYY HH24:MI') "
                    . " AND "
                    . " EQUIP_ID = " . $bol->codEquipBolFert . " ";

            $this->Read = $this->Conn->prepare($select);
            $this->Read->setFetchMode(PDO::FETCH_ASSOC);
            $this->Read->execute();
            $res1 = $this->Read->fetchAll();

            foreach ($res1 as $item1) {
                $v = $item1['QTDE'];
            }

            if ($v == 0) {

                if ($bol->hodometroInicialBolFert > 9999999) {
                    $bol->hodometroInicialBolFert = 0;
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
                        . " " . $bol->codMotoBolFert
                        . " , " . $bol->codEquipBolFert
                        . " , " . $bol->codEquipBombaBolFert
                        . " , " . $bol->codTurnoBolFert
                        . " , " . $bol->hodometroInicialBolFert
                        . " , " . $bol->osBolFert
                        . " , " . $bol->ativPrincBolFert
                        . " , " . $ajusteDataHoraDAO->dataHoraGMT($bol->dthrInicioBolFert)
                        . " , TO_DATE('" . $bol->dthrInicioBolFert . "','DD/MM/YYYY HH24:MI') "
                        . " , SYSDATE "
                        . " , 1 "
                        . " , " . $bol->statusConBolFert
                        . " )";

                $this->Create = $this->Conn->prepare($sql);
                $this->Create->execute();

                $select = " SELECT "
                        . " ID AS ID "
                        . " FROM "
                        . " PMM_BOLETIM_FERT "
                        . " WHERE "
                        . " DTHR_INICIAL_CEL = TO_DATE('" . $bol->dthrInicioBolFert . "','DD/MM/YYYY HH24:MI') "
                        . " AND "
                        . " EQUIP_ID = " . $bol->codEquipBolFert . " ";

                $this->Read = $this->Conn->prepare($select);
                $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                $this->Read->execute();
                $res2 = $this->Read->fetchAll();

                foreach ($res2 as $item2) {
                    $idBol = $item2['ID'];
                }

                foreach ($dadosAponta as $apont) {

                    if ($bol->idBolFert == $apont->idBolApontaFert) {

                        $select = " SELECT "
                                . " COUNT(*) AS QTDE "
                                . " FROM "
                                . " PMM_APONTAMENTO "
                                . " WHERE "
                                . " DTHR_CEL = TO_DATE('" . $apont->dthrApontaFert . "','DD/MM/YYYY HH24:MI') "
                                . " AND "
                                . " BOLETIM_ID = " . $idBol . " ";

                        $this->Read = $this->Conn->prepare($select);
                        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                        $this->Read->execute();
                        $res3 = $this->Read->fetchAll();

                        foreach ($res3 as $item3) {
                            $v = $item3['QTDE'];
                        }

                        if ($v == 0) {

                            $raio = "null";

                            if ($apont->paradaApontaFert == 0) {
                                $apont->paradaApontaFert = "null";
                                $raio = 45;
                            }

                            if ($apont->bocalApontaFert == 0) {
                                $apont->bocalApontaFert = "null";
                            }

                            if ($apont->pressaoApontaFert == 0) {
                                $apont->pressaoApontaFert = "null";
                            }

                            if ($apont->velocApontaFert == 0) {
                                $apont->velocApontaFert = "null";
                            }

                            $sql = "INSERT INTO PMM_APONTAMENTO_FERT ("
                                    . " BOLETIM_ID "
                                    . " , OS_NRO "
                                    . " , ATIVAGR_ID "
                                    . " , MOTPARADA_ID "
                                    . " , DTHR "
                                    . " , DTHR_CEL "
                                    . " , DTHR_TRANS "
                                    . " , BOCALBOMBA_ID "
                                    . " , PRESSAO "
                                    . " , VELOCIDADE "
                                    . " , RAIO "
                                    . " , LATITUDE "
                                    . " , LONGITUDE "
                                    . " , STATUS_CONEXAO "
                                    . " ) "
                                    . " VALUES ("
                                    . " " . $idBol
                                    . " , " . $apont->osApontaFert
                                    . " , " . $apont->ativApontaFert
                                    . " , " . $apont->paradaApontaFert
                                    . " , " . $ajusteDataHoraDAO->dataHoraGMT($apont->dthrApontaFert)
                                    . " , TO_DATE('" . $apont->dthrApontaFert . "','DD/MM/YYYY HH24:MI')"
                                    . " , SYSDATE "
                                    . " , " . $apont->bocalApontaFert
                                    . " , " . $apont->pressaoApontaFert
                                    . " , " . $apont->velocApontaFert
                                    . " , " . $raio
                                    . " , " . $apont->latitudeApontaFert
                                    . " , " . $apont->longitudeApontaFert
                                    . " , " . $apont->statusConApontaFert
                                    . " )";

                            $this->Create = $this->Conn->prepare($sql);
                            $this->Create->execute();

                            $select = " SELECT "
                                    . " ID AS ID "
                                    . " FROM "
                                    . " PMM_APONTAMENTO_FERT "
                                    . " WHERE "
                                    . " DTHR_CEL = TO_DATE('" . $apont->dthrApontaFert . "','DD/MM/YYYY HH24:MI') "
                                    . " AND "
                                    . " BOLETIM_ID = " . $idBol . " ";

                            $this->Read = $this->Conn->prepare($select);
                            $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                            $this->Read->execute();
                            $res5 = $this->Read->fetchAll();

                            foreach ($res5 as $item5) {
                                $idApont = $item5['ID'];
                            }

                            foreach ($dadosBolPneu as $bolPneu) {

                                if ($apont->idApontaFert == $bolPneu->idApontBolPneu) {

                                    $select = " SELECT "
                                            . " COUNT(*) AS QTDE "
                                            . " FROM "
                                            . " PMM_BOLETIM_PNEU "
                                            . " WHERE "
                                            . " FUNC_MATRIC = " . $bolPneu->funcBolPneu
                                            . " AND "
                                            . " EQUIP_ID = " . $bolPneu->equipBolPneu
                                            . " AND "
                                            . " DTHR_CEL = TO_DATE('" . $bolPneu->dthrBolPneu . "','DD/MM/YYYY HH24:MI') "
                                    ;

                                    $this->Read = $this->Conn->prepare($select);
                                    $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                                    $this->Read->execute();
                                    $res7 = $this->Read->fetchAll();

                                    foreach ($res7 as $item7) {
                                        $v = $item7['QTDE'];
                                    }

                                    if ($v == 0) {

                                        $sql = "INSERT INTO PMM_BOLETIM_PNEU ("
                                                . " APONTAMENTO_ID "
                                                . " , FUNC_MATRIC "
                                                . " , EQUIP_ID "
                                                . " , DTHR "
                                                . " , DTHR_CEL "
                                                . " , DTHR_TRANS "
                                                . " ) "
                                                . " VALUES ("
                                                . " " . $idApont
                                                . " , " . $bolPneu->funcBolPneu
                                                . " , " . $bolPneu->equipBolPneu
                                                . " , " . $ajusteDataHoraDAO->dataHoraGMT($bolPneu->dthrBolPneu)
                                                . " , TO_DATE('" . $bolPneu->dthrBolPneu . "','DD/MM/YYYY HH24:MI') "
                                                . " , SYSDATE "
                                                . " )";

                                        $this->Create = $this->Conn->prepare($sql);
                                        $this->Create->execute();

                                        foreach ($dadosItemPneu as $itemPneu) {

                                            if ($bolPneu->idBolPneu == $itemPneu->idBolItemMedPneu) {

                                                $select = " SELECT "
                                                        . " ID AS IDBOLPNEU "
                                                        . " FROM "
                                                        . " PMM_BOLETIM_PNEU "
                                                        . " WHERE "
                                                        . " FUNC_MATRIC = " . $bolPneu->funcBolPneu
                                                        . " AND "
                                                        . " EQUIP_ID = " . $bolPneu->equipBolPneu
                                                        . " AND "
                                                        . " DTHR_CEL = TO_DATE('" . $bolPneu->dthrBolPneu . "','DD/MM/YYYY HH24:MI') "
                                                ;

                                                $this->Read = $this->Conn->prepare($select);
                                                $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                                                $this->Read->execute();
                                                $res8 = $this->Read->fetchAll();

                                                foreach ($res8 as $item8) {
                                                    $idBolPneu = $item8['IDBOLPNEU'];
                                                }

                                                $select = " SELECT "
                                                        . " COUNT(*) AS QTDE "
                                                        . " FROM "
                                                        . " PMM_ITEM_MED_PNEU "
                                                        . " WHERE "
                                                        . " BOLETIM_PNEU_ID = " . $idBolPneu
                                                        . " AND "
                                                        . " NRO_PNEU LIKE '" . $itemPneu->nroPneuItemMedPneu . "'"
                                                        . " AND "
                                                        . " DTHR_CEL = TO_DATE('" . $itemPneu->dthrItemMedPneu . "','DD/MM/YYYY HH24:MI') ";

                                                $this->Read = $this->Conn->prepare($select);
                                                $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                                                $this->Read->execute();
                                                $res9 = $this->Read->fetchAll();

                                                foreach ($res9 as $item9) {
                                                    $v = $item9['QTDE'];
                                                }

                                                if ($v == 0) {

                                                    $sql = "INSERT INTO PMM_ITEM_MED_PNEU ("
                                                            . " BOLETIM_PNEU_ID "
                                                            . " , POSPNCONF_ID "
                                                            . " , NRO_PNEU "
                                                            . " , PRESSAO_ENC "
                                                            . " , PRESSAO_COL "
                                                            . " , DTHR "
                                                            . " , DTHR_CEL "
                                                            . " , DTHR_TRANS "
                                                            . " ) "
                                                            . " VALUES ("
                                                            . " " . $idBolPneu
                                                            . " , " . $itemPneu->posItemMedPneu
                                                            . " , '" . $itemPneu->nroPneuItemMedPneu . "'"
                                                            . " , " . $itemPneu->pressaoEncItemMedPneu
                                                            . " , " . $itemPneu->pressaoColItemMedPneu
                                                            . " , " . $ajusteDataHoraDAO->dataHoraGMT($itemPneu->dthrItemMedPneu)
                                                            . " , TO_DATE('" . $itemPneu->dthrItemMedPneu . "','DD/MM/YYYY HH24:MI') "
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
                        } else {

                            $select = " SELECT "
                                    . " ID AS ID "
                                    . " FROM "
                                    . " PMM_APONTAMENTO_FERT "
                                    . " WHERE "
                                    . " DTHR_CEL = TO_DATE('" . $apont->dthrApontaFert . "','DD/MM/YYYY HH24:MI') "
                                    . " AND "
                                    . " BOLETIM_ID = " . $idBol . " ";

                            $this->Read = $this->Conn->prepare($select);
                            $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                            $this->Read->execute();
                            $res10 = $this->Read->fetchAll();

                            foreach ($res10 as $item10) {
                                $idApont = $item10['ID'];
                            }

                            foreach ($dadosBolPneu as $bolPneu) {

                                if ($apont->idAponta == $bolPneu->idApontBolPneu) {

                                    $select = " SELECT "
                                            . " COUNT(*) AS QTDE "
                                            . " FROM "
                                            . " PMM_BOLETIM_PNEU "
                                            . " WHERE "
                                            . " FUNC_MATRIC = " . $bolPneu->funcBolPneu
                                            . " AND "
                                            . " EQUIP_ID = " . $bolPneu->equipBolPneu
                                            . " AND "
                                            . " DTHR_CEL = TO_DATE('" . $bolPneu->dthrBolPneu . "','DD/MM/YYYY HH24:MI') "
                                    ;

                                    $this->Read = $this->Conn->prepare($select);
                                    $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                                    $this->Read->execute();
                                    $res12 = $this->Read->fetchAll();

                                    foreach ($res12 as $item12) {
                                        $v = $item12['QTDE'];
                                    }

                                    if ($v == 0) {

                                        $sql = "INSERT INTO PMM_BOLETIM_PNEU ("
                                                . " APONTAMENTO_ID "
                                                . " , FUNC_MATRIC "
                                                . " , EQUIP_ID "
                                                . " , DTHR "
                                                . " , DTHR_CEL "
                                                . " , DTHR_TRANS "
                                                . " ) "
                                                . " VALUES ("
                                                . " " . $idApont
                                                . " , " . $bolPneu->funcBolPneu
                                                . " , " . $bolPneu->equipBolPneu
                                                . " , " . $ajusteDataHoraDAO->dataHoraGMT($bolPneu->dthrBolPneu)
                                                . " , TO_DATE('" . $bolPneu->dthrBolPneu . "','DD/MM/YYYY HH24:MI') "
                                                . " , SYSDATE "
                                                . " )";

                                        $this->Create = $this->Conn->prepare($sql);
                                        $this->Create->execute();

                                        foreach ($dadosItemPneu as $itemPneu) {

                                            if ($bolPneu->idBolPneu == $itemPneu->idBolItemMedPneu) {

                                                $select = " SELECT "
                                                        . " ID AS IDBOLPNEU "
                                                        . " FROM "
                                                        . " PMM_BOLETIM_PNEU "
                                                        . " WHERE "
                                                        . " FUNC_MATRIC = " . $bolPneu->funcBolPneu
                                                        . " AND "
                                                        . " EQUIP_ID = " . $bolPneu->equipBolPneu
                                                        . " AND "
                                                        . " DTHR_CEL = TO_DATE('" . $bolPneu->dthrBolPneu . "','DD/MM/YYYY HH24:MI') "
                                                ;

                                                $this->Read = $this->Conn->prepare($select);
                                                $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                                                $this->Read->execute();
                                                $res13 = $this->Read->fetchAll();

                                                foreach ($res13 as $item13) {
                                                    $idBolPneu = $item13['IDBOLPNEU'];
                                                }

                                                $select = " SELECT "
                                                        . " COUNT(*) AS QTDE "
                                                        . " FROM "
                                                        . " PMM_ITEM_MED_PNEU "
                                                        . " WHERE "
                                                        . " BOLETIM_PNEU_ID = " . $idBolPneu
                                                        . " AND "
                                                        . " NRO_PNEU LIKE '" . $itemPneu->nroPneuItemMedPneu . "'"
                                                        . " AND "
                                                        . " DTHR_CEL = TO_DATE('" . $itemPneu->dthrItemMedPneu . "','DD/MM/YYYY HH24:MI') ";

                                                $this->Read = $this->Conn->prepare($select);
                                                $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                                                $this->Read->execute();
                                                $res14 = $this->Read->fetchAll();

                                                foreach ($res14 as $item14) {
                                                    $v = $item14['QTDE'];
                                                }

                                                if ($v == 0) {

                                                    $sql = "INSERT INTO PMM_ITEM_MED_PNEU ("
                                                            . " BOLETIM_PNEU_ID "
                                                            . " , POSPNCONF_ID "
                                                            . " , NRO_PNEU "
                                                            . " , PRESSAO_ENC "
                                                            . " , PRESSAO_COL "
                                                            . " , DTHR "
                                                            . " , DTHR_CEL "
                                                            . " , DTHR_TRANS "
                                                            . " ) "
                                                            . " VALUES ("
                                                            . " " . $idBolPneu
                                                            . " , " . $itemPneu->posItemMedPneu
                                                            . " , '" . $itemPneu->nroPneuItemMedPneu . "'"
                                                            . " , " . $itemPneu->pressaoEncItemMedPneu
                                                            . " , " . $itemPneu->pressaoColItemMedPneu
                                                            . " , " . $ajusteDataHoraDAO->dataHoraGMT($itemPneu->dthrItemMedPneu)
                                                            . " , TO_DATE('" . $itemPneu->dthrItemMedPneu . "','DD/MM/YYYY HH24:MI') "
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
                    }
                }
            } else {

                $select = " SELECT "
                        . " ID AS ID "
                        . " FROM "
                        . " PMM_BOLETIM_FERT "
                        . " WHERE "
                        . " DTHR_INICIAL_CEL = TO_DATE('" . $bol->dthrInicioBolFert . "','DD/MM/YYYY HH24:MI') "
                        . " AND "
                        . " EQUIP_ID = " . $bol->codEquipBolFert . " ";

                $this->Read = $this->Conn->prepare($select);
                $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                $this->Read->execute();
                $res15 = $this->Read->fetchAll();

                foreach ($res15 as $item15) {
                    $idBol = $item15['ID'];
                }

                foreach ($dadosAponta as $apont) {

                    if ($bol->idBolFert == $apont->idBolApontaFert) {

                        $select = " SELECT "
                                . " COUNT(*) AS QTDE "
                                . " FROM "
                                . " PMM_APONTAMENTO_FERT "
                                . " WHERE "
                                . " DTHR_CEL = TO_DATE('" . $apont->dthrApontaFert . "','DD/MM/YYYY HH24:MI') "
                                . " AND "
                                . " BOLETIM_ID = " . $idBol . " ";

                        $this->Read = $this->Conn->prepare($select);
                        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                        $this->Read->execute();
                        $res16 = $this->Read->fetchAll();

                        foreach ($res16 as $item16) {
                            $v = $item16['QTDE'];
                        }

                        if ($v == 0) {

                            $raio = "null";

                            if ($apont->paradaApontaFert == 0) {
                                $apont->paradaApontaFert = "null";
                                $raio = 45;
                            }

                            if ($apont->bocalApontaFert == 0) {
                                $apont->bocalApontaFert = "null";
                            }

                            if ($apont->pressaoApontaFert == 0) {
                                $apont->pressaoApontaFert = "null";
                            }

                            if ($apont->velocApontaFert == 0) {
                                $apont->velocApontaFert = "null";
                            }

                            $sql = "INSERT INTO PMM_APONTAMENTO_FERT ("
                                    . " BOLETIM_ID "
                                    . " , OS_NRO "
                                    . " , ATIVAGR_ID "
                                    . " , MOTPARADA_ID "
                                    . " , DTHR "
                                    . " , DTHR_CEL "
                                    . " , DTHR_TRANS "
                                    . " , BOCALBOMBA_ID "
                                    . " , PRESSAO "
                                    . " , VELOCIDADE "
                                    . " , RAIO "
                                    . " , LATITUDE "
                                    . " , LONGITUDE "
                                    . " , STATUS_CONEXAO "
                                    . " ) "
                                    . " VALUES ("
                                    . " " . $idBol
                                    . " , " . $apont->osApontaFert
                                    . " , " . $apont->ativApontaFert
                                    . " , " . $apont->paradaApontaFert
                                    . " , " . $ajusteDataHoraDAO->dataHoraGMT($apont->dthrApontaFert)
                                    . " , TO_DATE('" . $apont->dthrApontaFert . "','DD/MM/YYYY HH24:MI')"
                                    . " , SYSDATE "
                                    . " , " . $apont->bocalApontaFert
                                    . " , " . $apont->pressaoApontaFert
                                    . " , " . $apont->velocApontaFert
                                    . " , " . $raio
                                    . " , " . $apont->latitudeApontaFert
                                    . " , " . $apont->longitudeApontaFert
                                    . " , " . $apont->statusConApontaFert
                                    . " )";

                            $this->Create = $this->Conn->prepare($sql);
                            $this->Create->execute();

                            $select = " SELECT "
                                    . " ID AS ID "
                                    . " FROM "
                                    . " PMM_APONTAMENTO_FERT "
                                    . " WHERE "
                                    . " DTHR_CEL = TO_DATE('" . $apont->dthrApontaFert . "','DD/MM/YYYY HH24:MI') "
                                    . " AND "
                                    . " BOLETIM_ID = " . $idBol . " ";

                            $this->Read = $this->Conn->prepare($select);
                            $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                            $this->Read->execute();
                            $res18 = $this->Read->fetchAll();

                            foreach ($res18 as $item18) {
                                $idApont = $item18['ID'];
                            }

                            foreach ($dadosBolPneu as $bolPneu) {

                                if ($apont->idApontaFert == $bolPneu->idApontBolPneu) {

                                    $select = " SELECT "
                                            . " COUNT(*) AS QTDE "
                                            . " FROM "
                                            . " PMM_BOLETIM_PNEU "
                                            . " WHERE "
                                            . " FUNC_MATRIC = " . $bolPneu->funcBolPneu
                                            . " AND "
                                            . " EQUIP_ID = " . $bolPneu->equipBolPneu
                                            . " AND "
                                            . " DTHR_CEL = TO_DATE('" . $bolPneu->dthrBolPneu . "','DD/MM/YYYY HH24:MI') ";

                                    $this->Read = $this->Conn->prepare($select);
                                    $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                                    $this->Read->execute();
                                    $res20 = $this->Read->fetchAll();

                                    foreach ($res20 as $item20) {
                                        $v = $item20['QTDE'];
                                    }

                                    if ($v == 0) {

                                        $sql = "INSERT INTO PMM_BOLETIM_PNEU ("
                                                . " APONTAMENTO_ID "
                                                . " , FUNC_MATRIC "
                                                . " , EQUIP_ID "
                                                . " , DTHR "
                                                . " , DTHR_CEL "
                                                . " , DTHR_TRANS "
                                                . " ) "
                                                . " VALUES ("
                                                . " " . $idApont
                                                . " , " . $bolPneu->funcBolPneu
                                                . " , " . $bolPneu->equipBolPneu
                                                . " , " . $ajusteDataHoraDAO->dataHoraGMT($imp->dthrImplemento)
                                                . " , TO_DATE('" . $bolPneu->dthrBolPneu . "','DD/MM/YYYY HH24:MI') "
                                                . " , SYSDATE "
                                                . " )";

                                        $this->Create = $this->Conn->prepare($sql);
                                        $this->Create->execute();

                                        foreach ($dadosItemPneu as $itemPneu) {

                                            if ($bolPneu->idBolPneu == $itemPneu->idBolItemMedPneu) {

                                                $select = " SELECT "
                                                        . " ID AS IDBOLPNEU "
                                                        . " FROM "
                                                        . " PMM_BOLETIM_PNEU "
                                                        . " WHERE "
                                                        . " FUNC_ID = " . $bolPneu->funcBolPneu
                                                        . " AND "
                                                        . " EQUIP_ID = " . $bolPneu->equipBolPneu
                                                        . " AND "
                                                        . " DTHR_CEL = TO_DATE('" . $bolPneu->dthrBolPneu . "','DD/MM/YYYY HH24:MI') "
                                                ;

                                                $this->Read = $this->Conn->prepare($select);
                                                $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                                                $this->Read->execute();
                                                $res21 = $this->Read->fetchAll();

                                                foreach ($res21 as $item21) {
                                                    $idBolPneu = $item21['IDBOLPNEU'];
                                                }

                                                $select = " SELECT "
                                                        . " COUNT(*) AS QTDE "
                                                        . " FROM "
                                                        . " PMM_ITEM_MED_PNEU "
                                                        . " WHERE "
                                                        . " BOLETIM_PNEU_ID = " . $idBolPneu
                                                        . " AND "
                                                        . " NRO_PNEU LIKE '" . $itemPneu->nroPneuItemMedPneu . "'"
                                                        . " AND "
                                                        . " DTHR_CEL = TO_DATE('" . $itemPneu->dthrItemMedPneu . "','DD/MM/YYYY HH24:MI') ";

                                                $this->Read = $this->Conn->prepare($select);
                                                $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                                                $this->Read->execute();
                                                $res22 = $this->Read->fetchAll();

                                                foreach ($res22 as $item22) {
                                                    $v = $item22['QTDE'];
                                                }

                                                if ($v == 0) {

                                                    $sql = "INSERT INTO PMM_ITEM_MED_PNEU ("
                                                            . " BOLETIM_PNEU_ID "
                                                            . " , POSPNCONF_ID "
                                                            . " , NRO_PNEU "
                                                            . " , PRESSAO_ENC "
                                                            . " , PRESSAO_COL "
                                                            . " , DTHR "
                                                            . " , DTHR_CEL "
                                                            . " , DTHR_TRANS "
                                                            . " ) "
                                                            . " VALUES ("
                                                            . " " . $idBolPneu
                                                            . " , " . $itemPneu->posItemMedPneu
                                                            . " , '" . $itemPneu->nroPneuItemMedPneu . "'"
                                                            . " , " . $itemPneu->pressaoEncItemMedPneu
                                                            . " , " . $itemPneu->pressaoColItemMedPneu
                                                            . " , " . $ajusteDataHoraDAO->dataHoraGMT($itemPneu->dthrItemMedPneu)
                                                            . " , TO_DATE('" . $itemPneu->dthrItemMedPneu . "','DD/MM/YYYY HH24:MI') "
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
                        } else {

                            $select = " SELECT "
                                    . " ID AS ID "
                                    . " FROM "
                                    . " PMM_APONTAMENTO_FERT "
                                    . " WHERE "
                                    . " DTHR_CEL = TO_DATE('" . $apont->dthrApontaFert . "','DD/MM/YYYY HH24:MI') "
                                    . " AND "
                                    . " BOLETIM_ID = " . $idBol . " ";

                            $this->Read = $this->Conn->prepare($select);
                            $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                            $this->Read->execute();
                            $res23 = $this->Read->fetchAll();

                            foreach ($res23 as $item23) {
                                $idApont = $item23['ID'];
                            }

                            foreach ($dadosBolPneu as $bolPneu) {

                                if ($apont->idApontaFert == $bolPneu->idApontBolPneu) {

                                    $select = " SELECT "
                                            . " COUNT(*) AS QTDE "
                                            . " FROM "
                                            . " PMM_BOLETIM_PNEU "
                                            . " WHERE "
                                            . " FUNC_MATRIC = " . $bolPneu->funcBolPneu
                                            . " AND "
                                            . " EQUIP_ID = " . $bolPneu->equipBolPneu
                                            . " AND "
                                            . " DTHR_CEL = TO_DATE('" . $bolPneu->dthrBolPneu . "','DD/MM/YYYY HH24:MI') "
                                    ;

                                    $this->Read = $this->Conn->prepare($select);
                                    $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                                    $this->Read->execute();
                                    $res25 = $this->Read->fetchAll();

                                    foreach ($res25 as $item25) {
                                        $v = $item25['QTDE'];
                                    }

                                    if ($v == 0) {

                                        $sql = "INSERT INTO PMM_BOLETIM_PNEU ("
                                                . " APONTAMENTO_ID "
                                                . " , FUNC_MATRIC "
                                                . " , EQUIP_ID "
                                                . " , DTHR "
                                                . " , DTHR_CEL "
                                                . " , DTHR_TRANS "
                                                . " ) "
                                                . " VALUES ("
                                                . " " . $idApont
                                                . " , " . $bolPneu->funcBolPneu
                                                . " , " . $bolPneu->equipBolPneu
                                                . " , " . $ajusteDataHoraDAO->dataHoraGMT($bolPneu->dthrBolPneu)
                                                . " , TO_DATE('" . $bolPneu->dthrBolPneu . "','DD/MM/YYYY HH24:MI') "
                                                . " , SYSDATE "
                                                . " )";

                                        $this->Create = $this->Conn->prepare($sql);
                                        $this->Create->execute();

                                        foreach ($dadosItemPneu as $itemPneu) {

                                            if ($bolPneu->idBolPneu == $itemPneu->idBolItemMedPneu) {

                                                $select = " SELECT "
                                                        . " ID AS IDBOLPNEU "
                                                        . " FROM "
                                                        . " PMM_BOLETIM_PNEU "
                                                        . " WHERE "
                                                        . " FUNC_MATRIC = " . $bolPneu->funcBolPneu
                                                        . " AND "
                                                        . " EQUIP_ID = " . $bolPneu->equipBolPneu
                                                        . " AND "
                                                        . " DTHR_CEL = TO_DATE('" . $bolPneu->dthrBolPneu . "','DD/MM/YYYY HH24:MI') "
                                                ;

                                                $this->Read = $this->Conn->prepare($select);
                                                $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                                                $this->Read->execute();
                                                $res26 = $this->Read->fetchAll();

                                                foreach ($res26 as $item26) {
                                                    $idBolPneu = $item26['IDBOLPNEU'];
                                                }

                                                $select = " SELECT "
                                                        . " COUNT(*) AS QTDE "
                                                        . " FROM "
                                                        . " PMM_ITEM_MED_PNEU "
                                                        . " WHERE "
                                                        . " BOLETIM_PNEU_ID = " . $idBolPneu
                                                        . " AND "
                                                        . " NRO_PNEU LIKE '" . $itemPneu->nroPneuItemMedPneu . "'"
                                                        . " AND "
                                                        . " DTHR_CEL = TO_DATE('" . $itemPneu->dthrItemMedPneu . "','DD/MM/YYYY HH24:MI') ";

                                                $this->Read = $this->Conn->prepare($select);
                                                $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                                                $this->Read->execute();
                                                $res12 = $this->Read->fetchAll();

                                                foreach ($res12 as $item12) {
                                                    $v = $item12['QTDE'];
                                                }

                                                if ($v == 0) {

                                                    $sql = "INSERT INTO PMM_ITEM_MED_PNEU ("
                                                            . " BOLETIM_PNEU_ID "
                                                            . " , POSPNCONF_ID "
                                                            . " , NRO_PNEU "
                                                            . " , PRESSAO_ENC "
                                                            . " , PRESSAO_COL "
                                                            . " , DTHR "
                                                            . " , DTHR_CEL "
                                                            . " , DTHR_TRANS "
                                                            . " ) "
                                                            . " VALUES ("
                                                            . " " . $idBolPneu
                                                            . " , " . $itemPneu->posItemMedPneu
                                                            . " , '" . $itemPneu->nroPneuItemMedPneu . "'"
                                                            . " , " . $itemPneu->pressaoEncItemMedPneu
                                                            . " , " . $itemPneu->pressaoColItemMedPneu
                                                            . " , " . $ajusteDataHoraDAO->dataHoraGMT($itemPneu->dthrItemMedPneu)
                                                            . " , TO_DATE('" . $itemPneu->dthrItemMedPneu . "','DD/MM/YYYY HH24:MI') "
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
                    }
                }
            }
        }

        return "GRAVFERT+id=" . $idBol . "_";
    }

}
