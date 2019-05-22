<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'Conn.class.php';
require_once 'AjusteDataHoraDAO.class.php';

/**
 * Description of InsApontamentoMMDAO
 *
 * @author anderson
 */
class InserirApontFert2DAO extends Conn {
    //put your code here

    /** @var PDO */
    private $Conn;

    public function salvarDados($dadosAponta, $dadosBolPneu, $dadosItemPneu) {

        $this->Conn = parent::getConn();

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        foreach ($dadosAponta as $apont) {

            $select = " SELECT "
                    . " COUNT(ID) AS QTDE "
                    . " FROM "
                    . " PMM_APONTAMENTO_FERT "
                    . " WHERE "
                    . " DTHR_CEL = TO_DATE('" . $apont->dthrApontaFert . "','DD/MM/YYYY HH24:MI') "
                    . " AND "
                    . " BOLETIM_ID = " . $apont->idExtBolApontaFert . " ";

            $this->Read = $this->Conn->prepare($select);
            $this->Read->setFetchMode(PDO::FETCH_ASSOC);
            $this->Read->execute();
            $res1 = $this->Read->fetchAll();

            foreach ($res1 as $item1) {
                $v = $item1['QTDE'];
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
                        . " " . $apont->idExtBolApontaFert
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
                        . " BOLETIM_ID = " . $apont->idExtBolApontaFert . " ";

                $this->Read = $this->Conn->prepare($select);
                $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                $this->Read->execute();
                $res3 = $this->Read->fetchAll();

                foreach ($res3 as $item3) {
                    $idApont = $item3['ID'];
                }

                foreach ($dadosBolPneu as $bolPneu) {

                    if ($apont->idBolApontaFert == $bolPneu->idApontBolPneu) {

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
                        $res5 = $this->Read->fetchAll();

                        foreach ($res5 as $item5) {
                            $v = $item5['QTDE'];
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
                                            . " DTHR_CEL = TO_DATE('" . $bolPneu->dthrBolPneu . "','DD/MM/YYYY HH24:MI') ";

                                    $this->Read = $this->Conn->prepare($select);
                                    $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                                    $this->Read->execute();
                                    $res6 = $this->Read->fetchAll();

                                    foreach ($res6 as $item6) {
                                        $idBolPneu = $item6['IDBOLPNEU'];
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
                                    $res7 = $this->Read->fetchAll();

                                    foreach ($res7 as $item7) {
                                        $v = $item7['QTDE'];
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
                                                . " , " . $itemPneu->nroPneuItemMedPneu
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
                        . " BOLETIM_ID = " . $apont->idExtBolApontaFert . " ";

                $this->Read = $this->Conn->prepare($select);
                $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                $this->Read->execute();
                $res8 = $this->Read->fetchAll();

                foreach ($res8 as $item8) {
                    $idApont = $item8['ID'];
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
                        $res10 = $this->Read->fetchAll();

                        foreach ($res10 as $item10) {
                            $v = $item10['QTDE'];
                        }

                        if ($v == 0) {

                            $sql = "INSERT INTO PMM_BOLETIM_PNEU ("
                                    . " APONTAMENTO_ID "
                                    . " , FUNC_MATRIC "
                                    . " , EQUIP_ID "
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
                                            . " DTHR_CEL = TO_DATE('" . $bolPneu->dthrBolPneu . "','DD/MM/YYYY HH24:MI') ";

                                    $this->Read = $this->Conn->prepare($select);
                                    $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                                    $this->Read->execute();
                                    $res11 = $this->Read->fetchAll();

                                    foreach ($res11 as $item11) {
                                        $idBolPneu = $item11['IDBOLPNEU'];
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
                                                . " , " . $itemPneu->nroPneuItemMedPneu
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
