<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('./model_v1/dao/LogDAO.class.php');
require_once('./model_v1/dao/BoletimFertDAO.class.php');
require_once('./model_v1/dao/ApontFertDAO.class.php');
require_once('./model_v1/dao/RecolhimentoFertDAO.class.php');

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
            $this->salvarApont($idBol, $bol->idBolFert, $dadosAponta);
            $this->salvarRecolhimento($idBol, $bol->idBolFert, $dadosRecolhimento);
        }
        return 'GRAVOU-BOLFECHADOFERT';
    }

    public function salvarDadosBolAbertoFert($info, $pagina) {

        $dados = $info['dado'];
        $this->salvarLog($dados, $pagina);

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
            $this->salvarApont($idBol, $bol->idBolFert, $dadosAponta);
        }
        return "GRAVFERT+id=" . $idBol . "_";
    }

    public function salvarDadosApontFert($info, $pagina) {

        $apontFertDAO = new ApontFertDAO();

        $dados = $info['dado'];
        $this->salvarLog($dados, $pagina);

        $jsonObjAponta = json_decode($dados);
        
        $dadosAponta = $jsonObjAponta->aponta;

        foreach ($dadosAponta as $apont) {
            $v = $apontFertDAO->verifApontFert($apont->idExtBolApontaFert, $apont);
            if ($v == 0) {
                $apontFertDAO->insApontFert($apont->idExtBolApontaFert, $apont);
            }
            $idApont = $apontFertDAO->idApontFert($apont->idExtBolApontaFert, $apont);
        }
        return 'GRAVOU-APONTAFERT';
    }

    private function salvarApont($idBolBD, $idBolCel, $dadosAponta) {
        $apontFertDAO = new ApontFertDAO();
        foreach ($dadosAponta as $apont) {
            if ($idBolCel == $apont->idBolApontaFert) {
                $v = $apontFertDAO->verifApontFert($idBolBD, $apont);
                if ($v == 0) {
                    $apontFertDAO->insApontFert($idBolBD, $apont);
                }
                $idApont = $apontFertDAO->idApontFert($idBolBD, $apont);
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
        $logDAO = new LogDAO();
        $logDAO->salvarDados($dados, $pagina);
    }

}
