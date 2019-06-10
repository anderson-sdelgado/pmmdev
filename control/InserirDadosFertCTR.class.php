<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('./model/dao/InserirLogDAO.class.php');
require_once('./model/dao/BoletimFertDAO.class.php');
require_once('./model/dao/ApontFertDAO.class.php');
require_once('./model/dao/RecolhimentoFertDAO.class.php');
require_once('./model/dao/BoletimPneuDAO.class.php');
require_once('./model/dao/ItemMedPneuDAO.class.php');

/**
 * Description of InserirDadosFertCTR
 *
 * @author anderson
 */
class InserirDadosFertCTR {

    //put your code here

    public function salvarDadosBolFechadoFert($info, $pagina) {

        $dados = $info['dado'];
        $this->salvarLog($dados, $pagina);

        $pos1 = strpos($dados, "_") + 1;
        $pos2 = strpos($dados, "|") + 1;
        $pos3 = strpos($dados, "#") + 1;
        $pos4 = strpos($dados, "?") + 1;

        $bolfert = substr($dados, 0, ($pos1 - 1));
        $apontfert = substr($dados, $pos1, (($pos2 - 1) - $pos1));
        $recol = substr($dados, $pos2, (($pos3 - 1) - $pos2));
        $bolpneu = substr($dados, $pos3, (($pos4 - 1) - $pos3));
        $itempneu = substr($dados, $pos4);

        $jsonObjBoletim = json_decode($bolfert);
        $jsonObjAponta = json_decode($apontfert);
        $jsonObjRecolhimento = json_decode($recol);
        $jsonObjBolPneu = json_decode($bolpneu);
        $jsonObjItemPneu = json_decode($itempneu);

        $dadosBoletim = $jsonObjBoletim->boletim;
        $dadosAponta = $jsonObjAponta->aponta;
        $dadosRecolhimento = $jsonObjRecolhimento->recolhimento;
        $dadosBolPneu = $jsonObjBolPneu->bolpneu;
        $dadosItemPneu = $jsonObjItemPneu->itempneu;

        $boletimFertDAO = new BoletimFertDAO();

        foreach ($dadosBoletim as $bol) {
            $v = $boletimFertDAO->verifBoletimFert($bol);
            if ($v == 0) {
                $boletimFertDAO->insBoletimFertFechado($bol);
            } else {
                $boletimFertDAO->updateBoletimFertFechado($bol);
            }
            $idBol = $boletimFertDAO->idBoletimMM($bol);
            $this->salvarApont($idBol, $bol->idBolFert, $dadosAponta, $dadosBolPneu, $dadosItemPneu);
            $this->salvarRendimento($idBol, $bol->idBolFert, $dadosRecolhimento);
        }
        return 'GRAVOU-BOLFECHADOFERT';
    }

    public function salvarDadosBolAbertoFert($info, $pagina) {

        $dados = $info['dado'];
        $this->salvarLog($dados, $pagina);

        $pos1 = strpos($dados, "_") + 1;
        $pos2 = strpos($dados, "|") + 1;
        $pos3 = strpos($dados, "#") + 1;

        $bolfert = substr($dados, 0, ($pos1 - 1));
        $apontfert = substr($dados, $pos1, (($pos2 - 1) - $pos1));
        $bolpneu = substr($dados, $pos2, (($pos3 - 1) - $pos2));
        $itempneu = substr($dados, $pos3);

        $jsonObjBoletim = json_decode($bolfert);
        $jsonObjAponta = json_decode($apontfert);
        $jsonObjBolPneu = json_decode($bolpneu);
        $jsonObjItemPneu = json_decode($itempneu);

        $dadosBoletim = $jsonObjBoletim->boletim;
        $dadosAponta = $jsonObjAponta->aponta;
        $dadosBolPneu = $jsonObjBolPneu->bolpneu;
        $dadosItemPneu = $jsonObjItemPneu->itempneu;

        $boletimFertDAO = new BoletimFertDAO();

        foreach ($dadosBoletim as $bol) {
            $v = $boletimFertDAO->verifBoletimFert($bol);
            if ($v == 0) {
                $boletimFertDAO->insBoletimFertAberto($bol);
            }
            $idBol = $boletimFertDAO->idBoletimFert($bol);
            $this->salvarApont($idBol, $bol->idBolFert, $dadosAponta, $dadosBolPneu, $dadosItemPneu);
        }
        return "GRAVFERT+id=" . $idBol . "_";
    }

    public function salvarDadosApontFert($info, $pagina) {

        $apontFertDAO = new ApontFertDAO();

        $dados = $info['dado'];
        $this->salvarLog($dados, $pagina);

        $pos1 = strpos($dados, "_") + 1;
        $pos2 = strpos($dados, "|") + 1;

        $apontfert = substr($dados, 0, ($pos1 - 1));
        $bolpneu = substr($dados, $pos1, (($pos2 - 1) - $pos1));
        $itempneu = substr($dados, $pos2);

        $jsonObjAponta = json_decode($apontfert);
        $jsonObjBolPneu = json_decode($bolpneu);
        $jsonObjItemPneu = json_decode($itempneu);
        
        $dadosAponta = $jsonObjAponta->aponta;
        $dadosBolPneu = $jsonObjBolPneu->bolpneu;
        $dadosItemPneu = $jsonObjItemPneu->itempneu;

        foreach ($dadosAponta as $apont) {
            $v = $apontFertDAO->verifApontFert($apont->idExtBolApontaFert, $apont);
            if ($v == 0) {
                $apontFertDAO->insApontFert($apont->idExtBolApontaFert, $apont);
            }
            $idApont = $apontFertDAO->idApontFert($apont->idExtBolApontaFert, $apont);
            $this->salvarBoletimPneu($idApont, $apont->idAponta, $dadosBolPneu, $dadosItemPneu);
        }
        return 'GRAVOU-APONTAFERT';
    }

    private function salvarApont($idBolBD, $idBolCel, $dadosAponta, $dadosBolPneu, $dadosItemPneu) {
        $apontFertDAO = new ApontFertDAO();
        foreach ($dadosAponta as $apont) {
            if ($idBolCel == $apont->idBolApontaFert) {
                $v = $apontFertDAO->verifApontFert($idBolBD, $apont);
                if ($v == 0) {
                    $apontFertDAO->insApontFert($idBolBD, $apont);
                }
                $idApont = $apontFertDAO->idApontFert($idBolBD, $apont);
                $this->salvarBoletimPneu($idApont, $apont->idApontaFert, $dadosBolPneu, $dadosItemPneu);
            }
        }
    }

    private function salvarBoletimPneu($idApontBD, $idApontCel, $dadosBolPneu, $dadosItemPneu) {
        $boletimPneuDAO = new BoletimPneuDAO();
        foreach ($dadosBolPneu as $bolPneu) {
            if ($idApontCel == $bolPneu->idApontBolPneu) {
                $v = $boletimPneuDAO->verifBoletimPneu($idApontBD, $bolPneu);
                if ($v == 0) {
                    $boletimPneuDAO->insBoletimPneu($idApontBD, $bolPneu);
                }
                $idBolPneu = $boletimPneuDAO->idBoletimPneu($idApontBD, $bolPneu);
                $this->salvarItemMedPneu($idBolPneu, $bolPneu->idBolPneu, $dadosItemPneu);
            }
        }
    }

    private function salvarItemMedPneu($idBolPneuBD, $idBolPneuCel, $dadosItemPneu) {
        $itemMedPneuDAO = new ItemMedPneuDAO();
        foreach ($dadosItemPneu as $itemPneu) {
            if ($idBolPneuCel == $itemPneu->idBolItemMedPneu) {
                $v = $itemMedPneuDAO->verifItemMedPneu($idBolPneuBD, $itemPneu);
                if ($v == 0) {
                    $itemMedPneuDAO->insItemMedPneu($idBolPneuBD, $itemPneu);
                }
            }
        }
    }

    private function salvarRecolhimento($idBolBD, $idBolCel, $dadosRecolhimento) {
        $recolhimentoFertDAO = new RecolhimentoFertDAO();
        foreach ($dadosRecolhimento as $rend) {
            if ($idBolCel == $rend->idBolRecol) {
                $v = $recolhimentoFertDAO->verifRecolhimentoFert($idBolBD, $rend);
                if ($v == 0) {
                    $recolhimentoFertDAO->insRecolhimentoFert($idBolBD, $rend);
                }
            }
        }
    }

    private function salvarLog($dados, $pagina) {
        $inserirLogDAO = new InserirLogDAO();
        $inserirLogDAO->salvarDados($dados, $pagina);
    }

}
