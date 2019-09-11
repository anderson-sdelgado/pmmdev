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

            $ret = $this->salvarBoletoFechado($dadosBoletim, $dadosAponta, $dadosRecolhimento);

            return 'BOLFECHADOFERT_' . $ret;
            
            
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

            $ret = $this->salvarBoletoAberto($dadosBoletim, $dadosAponta);

            return "BOLABERTOFERT_" . $ret;
            
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

            $ret = $this->salvarApontExt($dadosAponta);

            return 'APONTFERT_' . $ret;
            
        }
    }

    private function salvarBoletoFechado($dadosBoletim, $dadosAponta, $dadosRecolhimento) {
        
        $boletimFertDAO = new BoletimFertDAO();
        $idBolMMArray = array();

        foreach ($dadosBoletim as $bol) {
            $v = $boletimFertDAO->verifBoletimFert($bol);
            if ($v == 0) {
                $boletimFertDAO->insBoletimFertFechado($bol);
            } else {
                $boletimFertDAO->updateBoletimFertFechado($bol);
            }
            $idBolBD = $boletimFertDAO->idBoletimFert($bol);
            $this->salvarApontBol($idBolBD, $bol->idBolFert, $dadosAponta);
            $this->salvarRecolhimento($idBolBD, $bol->idBolFert, $dadosRecolhimento);
            $apontFertDAO = new ApontFertDAO();
            $qtdeApontBolFert = $apontFertDAO->verifQtdeApontFert($idBolBD);
            $idBolMMArray[] = array("idBolFert" => $bol->idBolFert, "qtdeApontBolFert" => $qtdeApontBolFert);
        }
        $dadoBol = array("boletim"=>$idBolMMArray);
        $retBol = json_encode($dadoBol);
        return $retBol;
    }
    
    private function salvarBoletoAberto($dadosBoletim, $dadosAponta) {
        $boletimFertDAO = new BoletimFertDAO();
        $idBolMMArray = array();
        
        foreach ($dadosBoletim as $bol) {
           $v = $boletimFertDAO->verifBoletimFert($bol);
           if ($v == 0) {
               $boletimFertDAO->insBoletimFertAberto($bol);
           }
           $idBolBD = $boletimFertDAO->idBoletimFert($bol);
           $retApont = $this->salvarApontBol($idBolBD, $bol->idBolFert, $dadosAponta);
           $idBolMMArray[] = array("idBolFert" => $bol->idBolFert, "idExtBolFert" => $idBolBD);
        }
        
        $dadoBol = array("boletim"=>$idBolMMArray);
        $retBol = json_encode($dadoBol);
        return $retBol . "|" . $retApont;
    }
    
    private function salvarApontBol($idBolBD, $idBolCel, $dadosAponta) {
        $apontFertDAO = new ApontFertDAO();
        $idApontArray = array();
        foreach ($dadosAponta as $apont) {
            if ($idBolCel == $apont->idBolApontFert) {
                $v = $apontFertDAO->verifApontFert($idBolBD, $apont);
                if ($v == 0) {
                    $apontFertDAO->insApontFert($idBolBD, $apont);
                }
                $idApontArray[] = array("idApontFert" => $apont->idApontFert);
            }
        }
        $dadoApont = array("apont"=>$idApontArray);
        $retApont = json_encode($dadoApont);
        return $retApont;
    }

    private function salvarApontExt($dadosAponta) {
        $apontFertDAO = new ApontFertDAO();
        $idApontArray = array();
        foreach ($dadosAponta as $apont) {
            $v = $apontFertDAO->verifApontFert($apont->idExtBolApontFert, $apont);
            if ($v == 0) {
                $apontFertDAO->insApontFert($apont->idExtBolApontFert, $apont);
            }
            $idApontArray[] = array("idApontFert" => $apont->idApontFert);
        }
        $dadoApont = array("apont"=>$idApontArray);
        $retApont = json_encode($dadoApont);
        return $retApont;
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
