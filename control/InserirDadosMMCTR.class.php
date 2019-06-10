<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('./model/dao/InserirLogDAO.class.php');
require_once('./model/dao/BoletimMMDAO.class.php');
require_once('./model/dao/ApontMMDAO.class.php');
require_once('./model/dao/ImplementoMMDAO.class.php');
require_once('./model/dao/RendimentoMMDAO.class.php');
require_once('./model/dao/BoletimPneuDAO.class.php');
require_once('./model/dao/ItemMedPneuDAO.class.php');

/**
 * Description of InserirDadosMM
 *
 * @author anderson
 */
class InserirDadosMMCTR {

    //put your code here

    public function salvarDadosBolFechadoMM($info, $pagina) {

        $dados = $info['dado'];
        $this->salvarLog($dados, $pagina);

        $pos1 = strpos($dados, "_") + 1;
        $pos2 = strpos($dados, "|") + 1;
        $pos3 = strpos($dados, "#") + 1;
        $pos4 = strpos($dados, "?") + 1;
        $pos5 = strpos($dados, "@") + 1;

        $bolmm = substr($dados, 0, ($pos1 - 1));
        $apontmm = substr($dados, $pos1, (($pos2 - 1) - $pos1));
        $impl = substr($dados, $pos2, (($pos3 - 1) - $pos2));
        $rend = substr($dados, $pos3, (($pos4 - 1) - $pos3));
        $bolpneu = substr($dados, $pos4, (($pos5 - 1) - $pos4));
        $itempneu = substr($dados, $pos5);

        $jsonObjBoletim = json_decode($bolmm);
        $jsonObjAponta = json_decode($apontmm);
        $jsonObjImplemento = json_decode($impl);
        $jsonObjRendimento = json_decode($rend);
        $jsonObjBolPneu = json_decode($bolpneu);
        $jsonObjItemPneu = json_decode($itempneu);

        $dadosBoletim = $jsonObjBoletim->boletim;
        $dadosAponta = $jsonObjAponta->aponta;
        $dadosImplemento = $jsonObjImplemento->implemento;
        $dadosRendimento = $jsonObjRendimento->rendimento;
        $dadosBolPneu = $jsonObjBolPneu->bolpneu;
        $dadosItemPneu = $jsonObjItemPneu->itempneu;

        $boletimMMDAO = new BoletimMMDAO();

        foreach ($dadosBoletim as $bol) {
            $v = $boletimMMDAO->verifBoletimMM($bol);
            if ($v == 0) {
                $boletimMMDAO->insBoletimMMFechado($bol);
            } else {
                $boletimMMDAO->updateBoletimMMFechado($bol);
            }
            $idBol = $boletimMMDAO->idBoletimMM($bol);
            $this->salvarApont($idBol, $bol->idBoletim, $dadosAponta, $dadosImplemento, $dadosBolPneu, $dadosItemPneu);
            $this->salvarRendimento($idBol, $bol->idBoletim, $dadosRendimento);
        }
        return 'GRAVOU-BOLFECHADO';
    }

    public function salvarDadosBolAbertoMM($info, $pagina) {

        $dados = $info['dado'];
        $this->salvarLog($dados, $pagina);

        $pos1 = strpos($dados, "_") + 1;
        $pos2 = strpos($dados, "|") + 1;
        $pos3 = strpos($dados, "#") + 1;
        $pos4 = strpos($dados, "?") + 1;

        $bolmm = substr($dados, 0, ($pos1 - 1));
        $apontmm = substr($dados, $pos1, (($pos2 - 1) - $pos1));
        $impl = substr($dados, $pos2, (($pos3 - 1) - $pos2));
        $bolpneu = substr($dados, $pos3, (($pos4 - 1) - $pos3));
        $itempneu = substr($dados, $pos4);

        $jsonObjBoletim = json_decode($bolmm);
        $jsonObjAponta = json_decode($apontmm);
        $jsonObjImplemento = json_decode($impl);
        $jsonObjBolPneu = json_decode($bolpneu);
        $jsonObjItemPneu = json_decode($itempneu);

        $dadosBoletim = $jsonObjBoletim->boletim;
        $dadosAponta = $jsonObjAponta->aponta;
        $dadosImplemento = $jsonObjImplemento->implemento;
        $dadosBolPneu = $jsonObjBolPneu->bolpneu;
        $dadosItemPneu = $jsonObjItemPneu->itempneu;

        $boletimMMDAO = new BoletimMMDAO();

        foreach ($dadosBoletim as $bol) {
            $v = $boletimMMDAO->verifBoletimMM($bol);
            if ($v == 0) {
                $boletimMMDAO->insBoletimMMAberto($bol);
            }
            $idBol = $boletimMMDAO->idBoletimMM($bol);
            $this->salvarApont($idBol, $bol->idBoletim, $dadosAponta, $dadosImplemento, $dadosBolPneu, $dadosItemPneu);
        }
        return "GRAVOU+id=" . $idBol . "_";
    }

    public function salvarDadosApontMM($info, $pagina) {

        $apontMMDAO = new ApontMMDAO();

        $dados = $info['dado'];
        $this->salvarLog($dados, $pagina);

        $pos1 = strpos($dados, "_") + 1;
        $pos2 = strpos($dados, "|") + 1;
        $pos3 = strpos($dados, "#") + 1;

        $apontmm = substr($dados, 0, ($pos1 - 1));
        $imp = substr($dados, $pos1, (($pos2 - 1) - $pos1));
        $bolpneu = substr($dados, $pos2, (($pos3 - 1) - $pos2));
        $itempneu = substr($dados, $pos3);

        $jsonObjAponta = json_decode($apontmm);
        $jsonObjImplemento = json_decode($imp);
        $jsonObjBolPneu = json_decode($bolpneu);
        $jsonObjItemPneu = json_decode($itempneu);

        $dadosAponta = $jsonObjAponta->aponta;
        $dadosImplemento = $jsonObjImplemento->implemento;
        $dadosBolPneu = $jsonObjBolPneu->bolpneu;
        $dadosItemPneu = $jsonObjItemPneu->itempneu;

        foreach ($dadosAponta as $apont) {
            $v = $apontMMDAO->verifApontMM($apont->idExtBolAponta, $apont);
            if ($v == 0) {
                $apontMMDAO->insApontMM($apont->idExtBolAponta, $apont);
            }
            $idApont = $apontMMDAO->idApontMM($apont->idExtBolAponta, $apont);
            $this->salvarImplemento($idApont, $apont->idAponta, $dadosImplemento);
            $this->salvarBoletimPneu($idApont, $apont->idAponta, $dadosBolPneu, $dadosItemPneu);
        }
        return 'GRAVOU-APONTAMM';
    }

    private function salvarApont($idBolBD, $idBolCel, $dadosAponta, $dadosImplemento, $dadosBolPneu, $dadosItemPneu) {
        $apontMMDAO = new ApontMMDAO();
        foreach ($dadosAponta as $apont) {
            if ($idBolCel == $apont->idBolAponta) {
                $v = $apontMMDAO->verifApontMM($idBolBD, $apont);
                if ($v == 0) {
                    $apontMMDAO->insApontMM($idBolBD, $apont);
                }
                $idApont = $apontMMDAO->idApontMM($idBolBD, $apont);
                $this->salvarImplemento($idApont, $apont->idAponta, $dadosImplemento);
                $this->salvarBoletimPneu($idApont, $apont->idAponta, $dadosBolPneu, $dadosItemPneu);
            }
        }
    }

    private function salvarImplemento($idApontaBD, $idApontaCel, $dadosImplemento) {
        $implementoMMDAO = new ImplementoMMDAO();
        foreach ($dadosImplemento as $imp) {
            if ($idApontaCel == $imp->idApontImplemento) {
                if ($imp->codEquipImplemento != 0) {
                    $v = $implementoMMDAO->verifImplementoMM($idApontaBD, $imp);
                    if ($v == 0) {
                        $implementoMMDAO->insImplementoMM($idApontaBD, $imp);
                    }
                }
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

    private function salvarRendimento($idBolBD, $idBolCel, $dadosRendimento) {
        $rendimentoMMDAO = new RendimentoMMDAO();
        foreach ($dadosRendimento as $rend) {
            if ($idBolCel == $rend->idBolRendimento) {
                $v = $rendimentoMMDAO->verifRendimentoMM($idBolBD, $rend);
                if ($v == 0) {
                    $rendimentoMMDAO->insRendimentoMM($idBolBD, $rend);
                }
            }
        }
    }

    private function salvarLog($dados, $pagina) {
        $inserirLogDAO = new InserirLogDAO();
        $inserirLogDAO->salvarDados($dados, $pagina);
    }

}
