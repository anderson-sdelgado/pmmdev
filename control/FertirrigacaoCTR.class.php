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
require_once('../model/dao/CabecPneuDAO.class.php');
require_once('../model/dao/ItemPneuDAO.class.php');
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
            $pos2 = strpos($dados, "?") + 1;
            $pos3 = strpos($dados, "@") + 1;
            $pos4 = strpos($dados, "|") + 1;
            

            $bolfert = substr($dados, 0, ($pos1 - 1));
            $apontfert = substr($dados, $pos1, (($pos2 - 1) - $pos1));
            $cabecPneu = substr($dados, $pos2, (($pos3 - 1) - $pos2));
            $itemPneu = substr($dados, $pos3, (($pos4 - 1) - $pos3));
            $recol = substr($dados, $pos4);

            $jsonObjBoletim = json_decode($bolfert);
            $jsonObjAponta = json_decode($apontfert);
            $jsonObjCabecPneu = json_decode($cabecPneu);
            $jsonObjItemPneu = json_decode($itemPneu);
            $jsonObjRecolhimento = json_decode($recol);

            $dadosBoletim = $jsonObjBoletim->boletim;
            $dadosAponta = $jsonObjAponta->aponta;
            $dadosCabecPneu = $jsonObjCabecPneu->cabecpneu;
            $dadosItemPneu = $jsonObjItemPneu->itempneu;
            $dadosRecolhimento = $jsonObjRecolhimento->recolhimento;

            $ret = $this->salvarBoletoFechado($dadosBoletim, $dadosAponta, $dadosRecolhimento, $dadosCabecPneu, $dadosItemPneu);

            return $ret;
            
            
        }
    }

    public function salvarBolAberto($versao, $info, $pagina) {

        $dados = $info['dado'];
        $pagina = $pagina . '-' . $versao;
        $this->salvarLog($dados, $pagina);

        $versao = str_replace("_", ".", $versao);

        if ($versao >= 2.00) {

            $pos1 = strpos($dados, "_") + 1;
            $pos2 = strpos($dados, "?") + 1;
            $pos3 = strpos($dados, "@") + 1;

            $bolfert = substr($dados, 0, ($pos1 - 1));
            $apontfert = substr($dados, $pos1, (($pos2 - 1) - $pos1));
            $cabecPneu = substr($dados, $pos2, (($pos3 - 1) - $pos2));
            $itemPneu = substr($dados, $pos3);

            $jsonObjBoletim = json_decode($bolfert);
            $jsonObjAponta = json_decode($apontfert);
            $jsonObjCabecPneu = json_decode($cabecPneu);
            $jsonObjItemPneu = json_decode($itemPneu);
            
            $dadosBoletim = $jsonObjBoletim->boletim;
            $dadosAponta = $jsonObjAponta->aponta;
            $dadosCabecPneu = $jsonObjCabecPneu->cabecpneu;
            $dadosItemPneu = $jsonObjItemPneu->itempneu;

            $ret = $this->salvarBoletoAberto($dadosBoletim, $dadosAponta, $dadosCabecPneu, $dadosItemPneu);

            return $ret;
            
        }
    }

    public function salvarApont($versao, $info, $pagina) {

        $dados = $info['dado'];
        $pagina = $pagina . '-' . $versao;
        $this->salvarLog($dados, $pagina);

        $versao = str_replace("_", ".", $versao);

        if ($versao >= 2.00) {

            $pos1 = strpos($dados, "?") + 1;
            $pos2 = strpos($dados, "@") + 1;
            
            $apontfert = substr($dados, 0, ($pos1 - 1));
            $cabecPneu = substr($dados, $pos1, (($pos2 - 1) - $pos1));
            $itemPneu = substr($dados, $pos2);
            
            $jsonObjAponta = json_decode($apontfert);
            $jsonObjCabecPneu = json_decode($cabecPneu);
            $jsonObjItemPneu = json_decode($itemPneu);
            
            $dadosAponta = $jsonObjAponta->aponta;
            $dadosCabecPneu = $jsonObjCabecPneu->cabecpneu;
            $dadosItemPneu = $jsonObjItemPneu->itempneu;

            $ret = $this->salvarApontExt($dadosAponta, $dadosCabecPneu, $dadosItemPneu);

            return $ret;
            
        }
    }

    private function salvarBoletoFechado($dadosBoletim, $dadosAponta, $dadosRecolhimento, $dadosCabecPneu, $dadosItemPneu) {
        
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
            $this->salvarApontBol($idBolBD, $bol->idBolFert, $dadosAponta, $dadosCabecPneu, $dadosItemPneu);
            $this->salvarRecolhimento($idBolBD, $bol->idBolFert, $dadosRecolhimento);
            $apontFertDAO = new ApontFertDAO();
            $qtdeApontBolFert = $apontFertDAO->verifQtdeApontFert($idBolBD);
            $idBolMMArray[] = array("idBolFert" => $bol->idBolFert, "qtdeApontBolFert" => $qtdeApontBolFert);
        }
        $dadoBol = array("boletim"=>$idBolMMArray);
        $retBol = json_encode($dadoBol);
        return 'BOLFECHADOFERT_' . $retBol;
    }
    
    private function salvarBoletoAberto($dadosBoletim, $dadosAponta, $dadosCabecPneu, $dadosItemPneu) {
        $boletimFertDAO = new BoletimFertDAO();
        $idBolMMArray = array();
        
        foreach ($dadosBoletim as $bol) {
           $v = $boletimFertDAO->verifBoletimFert($bol);
           if ($v == 0) {
               $boletimFertDAO->insBoletimFertAberto($bol);
           }
           $idBolBD = $boletimFertDAO->idBoletimFert($bol);
           $retApont = $this->salvarApontBol($idBolBD, $bol->idBolFert, $dadosAponta, $dadosCabecPneu, $dadosItemPneu);
           $idBolMMArray[] = array("idBolFert" => $bol->idBolFert, "idExtBolFert" => $idBolBD);
        }
        
        $dadoBol = array("boletim"=>$idBolMMArray);
        $retBol = json_encode($dadoBol);
        return "BOLABERTOFERT_" . $retBol . "|" . $retApont;
    }
    
    private function salvarApontBol($idBolBD, $idBolCel, $dadosAponta, $dadosCabecPneu, $dadosItemPneu) {
        $apontFertDAO = new ApontFertDAO();
        $idApontArray = array();
        foreach ($dadosAponta as $apont) {
            if ($idBolCel == $apont->idBolApontFert) {
                $v = $apontFertDAO->verifApontFert($idBolBD, $apont);
                if ($v == 0) {
                    $apontFertDAO->insApontFert($idBolBD, $apont);
                }
                $idApont = $apontFertDAO->idApontFert($idBolBD, $apont);
                $this->salvarCabecPneu($idApont, $apont->idApontFert, $dadosCabecPneu, $dadosItemPneu);
                $idApontArray[] = array("idApontFert" => $apont->idApontFert);
            }
        }
        $dadoApont = array("apont"=>$idApontArray);
        $retApont = json_encode($dadoApont);
        return $retApont;
    }

    private function salvarApontExt($dadosAponta, $dadosCabecPneu, $dadosItemPneu) {
        $apontFertDAO = new ApontFertDAO();
        $idApontArray = array();
        foreach ($dadosAponta as $apont) {
            $v = $apontFertDAO->verifApontFert($apont->idExtBolApontFert, $apont);
            if ($v == 0) {
                $apontFertDAO->insApontFert($apont->idExtBolApontFert, $apont);
            }
            $idApont = $apontFertDAO->idApontFert($apont->idExtBolApontFert, $apont);
            $this->salvarCabecPneu($idApont, $apont->idApontFert, $dadosCabecPneu, $dadosItemPneu);
            $idApontArray[] = array("idApontFert" => $apont->idApontFert);
        }
        $dadoApont = array("apont"=>$idApontArray);
        $retApont = json_encode($dadoApont);
        return 'APONTFERT_' . $retApont;
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

    private function salvarCabecPneu($idApontaBD, $idApontaCel, $dadosCabecPneu, $dadosItemPneu) {
        $cabecPneuDAO = new CabecPneuDAO();
        foreach ($dadosCabecPneu as $cabecPneu) {
            if ($idApontaCel == $cabecPneu->idApontCabecPneu) {
                $v = $cabecPneuDAO->verifCabecPneu($idApontaBD, $cabecPneu);
                if ($v == 0) {
                    $cabecPneuDAO->insCabecPneu($idApontaBD, $cabecPneu, 2);
                }
                $idCabecPneuBD = $cabecPneuDAO->idCabecPneu($idApontaBD, $cabecPneu);
                $this->salvarItemPneu($idCabecPneuBD, $cabecPneu->idCabecPneu, $dadosItemPneu);
            }
        }
    }
    
    private function salvarItemPneu($idCabecPneuBD, $idCabecPneuCel, $dadosItemPneu) {
        $itemPneuDAO = new ItemPneuDAO();
        foreach ($dadosItemPneu as $itemPneu) {
            if ($idCabecPneuCel == $itemPneu->idCabecItemPneu) {
                $v = $itemPneuDAO->verifItemPneu($idCabecPneuBD, $itemPneu);
                if ($v == 0) {
                    $itemPneuDAO->insItemPneu($idCabecPneuBD, $itemPneu);
                }
            }
        }
    }
    
    private function salvarLog($dados, $pagina) {
        $logDAO = new LogDAO();
        $logDAO->salvarDados($dados, $pagina);
    }

    
}
