<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/dao/LogDAO.class.php');
require_once('../model/dao/BoletimFertDAO.class.php');
require_once('../model/dao/ApontFertDAO.class.php');
require_once('../model/dao/RecolhimentoFertDAO.class.php');

/**
 * Description of InserirDadosFertCTR
 *
 * @author anderson
 */
class FertirrigacaoCTR {

    //put your code here

    public function salvarBolFechado($versao, $info, $pagina) {

        $dados = $info['dado'];
        $pagina = $pagina . '-' . $versao;
        $this->salvarLog($dados, $pagina);

        $versao = str_replace("_", ".", $versao);

        if ($versao >= 2.00) {

            $pos1 = strpos($dados, "_") + 1;
            $pos2 = strpos($dados, "|") + 1;

            $bolfert = substr($dados, 0, ($pos1 - 1));
            $apontfert = substr($dados, $pos1, (($pos2 - 1) - $pos1));
            $recol = substr($dados, $pos2);

            $jsonObjBoletim = json_decode($bolfert);
            $jsonObjAponta = json_decode($apontfert);
            $jsonObjRecolhimento = json_decode($recol);

            $dadosBoletim = $jsonObjBoletim->boletim;
            $dadosAponta = $jsonObjAponta->aponta;
            $dadosRecolhimento = $jsonObjRecolhimento->recolhimento;

            $boletimFertDAO = new BoletimFertDAO();

            foreach ($dadosBoletim as $bol) {
                $v = $boletimFertDAO->verifBoletimFert($bol);
                if ($v == 0) {
                    $boletimFertDAO->insBoletimFertFechado($bol);
                } else {
                    $boletimFertDAO->updateBoletimFertFechado($bol);
                }
                $idBol = $boletimFertDAO->idBoletimFert($bol);
                $this->salvarApontBol($idBol, $bol->idBolFert, $dadosAponta);
                $this->salvarRecolhimento($idBol, $bol->idBolFert, $dadosRecolhimento);
            }
            return 'GRAVOU-BOLFECHADOFERT';
        }
    }

    public function salvarBolAberto($versao, $info, $pagina) {

        $dados = $info['dado'];
        $pagina = $pagina . '-' . $versao;
        $this->salvarLog($dados, $pagina);

        $versao = str_replace("_", ".", $versao);

        if ($versao >= 2.00) {

            $pos1 = strpos($dados, "_") + 1;

            $bolfert = substr($dados, 0, ($pos1 - 1));
            $apontfert = substr($dados, $pos1);

            $jsonObjBoletim = json_decode($bolfert);
            $jsonObjAponta = json_decode($apontfert);

            $dadosBoletim = $jsonObjBoletim->boletim;
            $dadosAponta = $jsonObjAponta->aponta;

            $boletimFertDAO = new BoletimFertDAO();

            foreach ($dadosBoletim as $bol) {
                $v = $boletimFertDAO->verifBoletimFert($bol);
                if ($v == 0) {
                    $boletimFertDAO->insBoletimFertAberto($bol);
                }
                $idBol = $boletimFertDAO->idBoletimFert($bol);
                $this->salvarApontBol($idBol, $bol->idBolFert, $dadosAponta);
            }

            return "GRAVFERT+id=" . $idBol . "_";
        }
    }

    public function salvarApont($versao, $info, $pagina) {

        $dados = $info['dado'];
        $pagina = $pagina . '-' . $versao;
        $this->salvarLog($dados, $pagina);

        $versao = str_replace("_", ".", $versao);

        if ($versao >= 2.00) {

            $jsonObjAponta = json_decode($dados);
            $dadosAponta = $jsonObjAponta->aponta;

            $apontFertDAO = new ApontFertDAO();

            foreach ($dadosAponta as $apont) {
                $v = $apontFertDAO->verifApontFert($apont->idExtBolApontFert, $apont);
                if ($v == 0) {
                    $apontFertDAO->insApontFert($apont->idExtBolApontFert, $apont);
                }
            }
            return 'GRAVOU-APONTAFERT';
        }
    }

    private function salvarApontBol($idBolBD, $idBolCel, $dadosAponta) {
        $apontFertDAO = new ApontFertDAO();
        foreach ($dadosAponta as $apont) {
            if ($idBolCel == $apont->idBolApontFert) {
                $v = $apontFertDAO->verifApontFert($idBolBD, $apont);
                if ($v == 0) {
                    $apontFertDAO->insApontFert($idBolBD, $apont);
                }
            }
        }
    }

    private function salvarRecolhimento($idBolBD, $idBolCel, $dadosRecolhimento) {
        $recolhimentoFertDAO = new RecolhimentoFertDAO();
        foreach ($dadosRecolhimento as $rend) {
            if ($idBolCel == $rend->idBolRecolhFert) {
                $v = $recolhimentoFertDAO->verifRecolhimentoFert($idBolBD, $rend);
                if ($v == 0) {
                    $recolhimentoFertDAO->insRecolhimentoFert($idBolBD, $rend);
                }
            }
        }
    }

    private function salvarLog($dados, $pagina) {
        $logDAO = new LogDAO();
        $logDAO->salvarDados($dados, $pagina);
    }

}
