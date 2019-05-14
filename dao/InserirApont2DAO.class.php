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
class InserirApont2DAO extends Conn {
    //put your code here

    /** @var PDO */
    private $Conn;

    public function salvarDados($dadosAponta, $dadosImplemento, $dadosBolPneu, $dadosItemPneu) {

        $this->Conn = parent::getConn();

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();
        
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

                $sql = "INSERT INTO PMM_APONTAMENTO ("
                        . " BOLETIM_ID "
                        . " , OS_NRO "
                        . " , ATIVAGR_ID "
                        . " , MOTPARADA_ID "
                        . " , DTHR "
                        . " , DTHR_CEL "
                        . " , DTHR_TRANS "
                        . " , NRO_EQUIP_TRANSB "
                        . " , LATITUDE "
                        . " , LONGITUDE "
                        . " , STATUS_CONEXAO "
                        . " ) "
                        . " VALUES ("
                        . " " . $apont->idExtBolAponta
                        . " , " . $apont->osAponta
                        . " , " . $apont->atividadeAponta
                        . " , " . $apont->paradaAponta
                        . " , " . $ajusteDataHoraDAO->dataHoraIdBoletim($apont->idExtBolAponta, $apont->dthrAponta)
                        . " , TO_DATE('" . $apont->dthrAponta . "','DD/MM/YYYY HH24:MI')"
                        . " , SYSDATE "
                        . " , " . $apont->transbordoAponta
                        . " , " . $apont->latitudeAponta
                        . " , " . $apont->longitudeAponta
                        . " , " . $apont->statusConAponta
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
                                        . " , DTHR "
                                        . " , DTHR_CEL "
                                        . " , DTHR_TRANS "
                                        . " ) "
                                        . " VALUES ("
                                        . " " . $idApont
                                        . " , " . $imp->codEquipImplemento
                                        . " , " . $imp->posImplemento
                                        . " , " . $ajusteDataHoraDAO->dataHoraIdApont($idApont, $imp->dthrImplemento)
                                        . " , TO_DATE('" . $imp->dthrImplemento . "','DD/MM/YYYY HH24:MI') "
                                        . " , SYSDATE "
                                        . " )";

                                $this->Create = $this->Conn->prepare($sql);
                                $this->Create->execute();
                            }
                        }
                    }
                }

                foreach ($dadosBolPneu as $bolPneu) {

                    if ($apont->idAponta == $bolPneu->idApontBolPneu) {

                        $select = " SELECT "
                                . " COUNT(*) AS QTDE "
                                . " FROM "
                                . " PMM_BOLETIM_PNEU "
                                . " WHERE "
                                . " FUNC_ID = " . $bolPneu->funcBolPneu
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
                                    . " , FUNC_ID "
                                    . " , EQUIP_ID "
                                    . " , DTHR "
                                    . " , DTHR_CEL "
                                    . " , DTHR_TRANS "
                                    . " ) "
                                    . " VALUES ("
                                    . " " . $idApont
                                    . " , " . $bolPneu->funcBolPneu
                                    . " , " . $bolPneu->equipBolPneu
                                    . " , " . $ajusteDataHoraDAO->dataHoraIdApont($idApont, $bolPneu->dthrBolPneu)
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
                                                . " , "  . $ajusteDataHoraDAO->dataHoraIdBolPneu($idBolPneu, $itemPneu->dthrItemMedPneu)
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
                        . " PMM_APONTAMENTO "
                        . " WHERE "
                        . " DTHR_CEL = TO_DATE('" . $apont->dthrAponta . "','DD/MM/YYYY HH24:MI') "
                        . " AND "
                        . " BOLETIM_ID = " . $apont->idExtBolAponta . " ";

                $this->Read = $this->Conn->prepare($select);
                $this->Read->setFetchMode(PDO::FETCH_ASSOC);
                $this->Read->execute();
                $res8 = $this->Read->fetchAll();

                foreach ($res8 as $item8) {
                    $idApont = $item8['ID'];
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
                            $res9 = $this->Read->fetchAll();

                            foreach ($res9 as $item9) {
                                $v = $item9['QTDE'];
                            }

                            if ($v == 0) {

                                $sql = "INSERT INTO PMM_IMPLEMENTO ("
                                        . " APONTAMENTO_ID "
                                        . " , NRO_EQUIP "
                                        . " , POS_EQUIP "
                                        . " , DTHR "
                                        . " , DTHR_CEL "
                                        . " , DTHR_TRANS "
                                        . " ) "
                                        . " VALUES ("
                                        . " " . $idApont
                                        . " , " . $imp->codEquipImplemento
                                        . " , " . $imp->posImplemento
                                        . " , " . $ajusteDataHoraDAO->dataHoraIdApont($idApont, $imp->dthrImplemento)
                                        . " , TO_DATE('" . $imp->dthrImplemento . "','DD/MM/YYYY HH24:MI') "
                                        . " , SYSDATE "
                                        . " )";

                                $this->Create = $this->Conn->prepare($sql);
                                $this->Create->execute();
                            }
                        }
                    }
                }

                foreach ($dadosBolPneu as $bolPneu) {

                    if ($apont->idAponta == $bolPneu->idApontBolPneu) {

                        $select = " SELECT "
                                . " COUNT(*) AS QTDE "
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
                        $res10 = $this->Read->fetchAll();

                        foreach ($res10 as $item10) {
                            $v = $item10['QTDE'];
                        }

                        if ($v == 0) {

                            $sql = "INSERT INTO PMM_BOLETIM_PNEU ("
                                    . " APONTAMENTO_ID "
                                    . " , FUNC_ID "
                                    . " , EQUIP_ID "
                                    . " , DTHR_CEL "
                                    . " , DTHR_TRANS "
                                    . " ) "
                                    . " VALUES ("
                                    . " " . $idApont
                                    . " , " . $bolPneu->funcBolPneu
                                    . " , " . $bolPneu->equipBolPneu
                                    . " , " . $ajusteDataHoraDAO->dataHoraIdApont($idApont, $bolPneu->dthrBolPneu)
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
                                                . " , "  . $ajusteDataHoraDAO->dataHoraIdBolPneu($idBolPneu, $itemPneu->dthrItemMedPneu)
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
