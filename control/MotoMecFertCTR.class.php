<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/dao/LogEnvioDAO.class.php');
require_once('../model/dao/BoletimMMFertDAO.class.php');
require_once('../model/dao/ApontMMFertDAO.class.php');
require_once('../model/dao/ImplementoMMDAO.class.php');
require_once('../model/dao/LeiraDAO.class.php');
require_once('../model/dao/RendimentoMMDAO.class.php');
require_once('../model/dao/RecolhimentoFertDAO.class.php');
require_once('../model/dao/MotoMecDAO.class.php');
/**
 * Description of MotoMecFert
 *
 * @author anderson
 */
class MotoMecFertCTR {
    
    private $base = 2;

    public function salvarBolAbertoMMFert($versao, $info, $pagina) {

        $dados = $info['dado'];
        $this->salvarLog($dados, $pagina, $versao);
        $versao = str_replace("_", ".", $versao);
        
        if ($versao >= 2.00) {
            
            $pos1 = strpos($dados, "_") + 1;
            $pos2 = strpos($dados, "|") + 1;
            $pos3 = strpos($dados, "#") + 1;

            $bol = substr($dados, 0, ($pos1 - 1));
            $apont = substr($dados, $pos1, (($pos2 - 1) - $pos1));
            $impl = substr($dados, $pos2, (($pos3 - 1) - $pos2));
            $movleira = substr($dados, $pos3);

            $jsonObjBoletim = json_decode($bol);
            $jsonObjApont = json_decode($apont);
            $jsonObjImplemento = json_decode($impl);
            $jsonObjMovLeira = json_decode($movleira);

            $dadosBoletim = $jsonObjBoletim->boletim;
            $dadosApont = $jsonObjApont->apont;
            $dadosImplemento = $jsonObjImplemento->implemento;
            $dadosMovLeira = $jsonObjMovLeira->movleira;

            $ret = $this->salvarBoletimAbertoMMFert($dadosBoletim, $dadosApont, $dadosImplemento, $dadosMovLeira);

            return $ret;
        }
    }
    
    public function salvarBolFechadoMMFert($versao, $info, $pagina) {

        $dados = $info['dado'];
        $this->salvarLog($dados, $pagina, $versao);
        $versao = str_replace("_", ".", $versao);

        if ($versao >= 2.00) {

            $pos1 = strpos($dados, "_") + 1;
            $pos2 = strpos($dados, "|") + 1;
            $pos3 = strpos($dados, "#") + 1;
            $pos4 = strpos($dados, "?") + 1;
            $pos5 = strpos($dados, "=") + 1;

            $bol = substr($dados, 0, ($pos1 - 1));
            $apont = substr($dados, $pos1, (($pos2 - 1) - $pos1));
            $impl = substr($dados, $pos2, (($pos3 - 1) - $pos2));
            $movleira = substr($dados, $pos3, (($pos4 - 1) - $pos3));
            $rend = substr($dados, $pos4, (($pos5 - 1) - $pos4));
            $recolh = substr($dados, $pos5);
            
            $jsonObjBoletim = json_decode($bol);
            $jsonObjApont = json_decode($apont);
            $jsonObjImpl = json_decode($impl);
            $jsonObjMovLeira = json_decode($movleira);
            $jsonObjRend = json_decode($rend);
            $jsonObjRecolh = json_decode($recolh);
            
            $dadosBoletim = $jsonObjBoletim->boletim;
            $dadosApont = $jsonObjApont->apont;
            $dadosImplemento = $jsonObjImpl->implemento;
            $dadosMovLeira = $jsonObjMovLeira->movleira;
            $dadosRendimento = $jsonObjRend->rendimento;
            $dadosRecolhimento = $jsonObjRecolh->recolhimento;

            $ret = $this->salvarBoletimFechMMFert($dadosBoletim, $dadosApont, $dadosImplemento, $dadosMovLeira, $dadosRendimento, $dadosRecolhimento);

            return $ret;
        }
    }
    
    private function salvarLog($dados, $pagina, $versao) {
        $logEnvioDAO = new LogEnvioDAO();
        $logEnvioDAO->salvarDados($dados, $pagina, $versao, $this->base);
    }

    private function salvarBoletimAbertoMMFert($dadosBoletim, $dadosApont, $dadosImplemento, $dadosMovLeira) {
        
        $boletimMMFertDAO = new BoletimMMFertDAO();
        $idBolMMArray = array();
        
        foreach ($dadosBoletim as $bol) {
            if($bol->tipoBolMMFert === 1){
                $v = $boletimMMFertDAO->verifBoletimMM($bol, $this->base);
                if ($v == 0) {
                    $boletimMMFertDAO->insBoletimMMAberto($bol, $this->base);
                }
                $idBolBD = $boletimMMFertDAO->idBoletimMM($bol, $this->base);
                $retApont = $this->salvarApontMM($idBolBD, $bol->idBolMMFert, $dadosApont, $dadosImplemento);
                $retMovLeira = $this->salvarMovLeiraMM($idBolBD, $bol->idBolMMFert, $dadosMovLeira);
            }
            else{
                $v = $boletimMMFertDAO->verifBoletimFert($bol, $this->base);
                if ($v == 0) {
                    $boletimMMFertDAO->insBoletimFertAberto($bol, $this->base);
                }
                $idBolBD = $boletimMMFertDAO->idBoletimFert($bol, $this->base);
                $retApont = $this->salvarApontFert($idBolBD, $bol->idBolMMFert, $dadosApont);
                $retMovLeira = $this->salvarMovLeiraMM($idBolBD, $bol->idBolMMFert, $dadosMovLeira);
            }
            $idBolMMArray[] = array("idBolMMFert" => $bol->idBolMMFert, "idExtBolMMFert" => $idBolBD);
        }
        
        $dadoBol = array("boletim"=>$idBolMMArray);
        $retBol = json_encode($dadoBol);
        return 'BOLABERTOMM_' . $retBol . "|" . $retApont . "#" . $retMovLeira;
        
    }

    private function salvarBoletimFechMMFert($dadosBoletim, $dadosApont, $dadosImplemento, $dadosMovLeira, $dadosRendimento, $dadosRecolhimento) {
        
        $boletimMMFertDAO = new BoletimMMFertDAO();
        $idBolMMArray = array();
        
        foreach ($dadosBoletim as $bol) {

            if($bol->tipoBolMMFert === 1){
                $v = $boletimMMFertDAO->verifBoletimMM($bol, $this->base);
                if ($v == 0) {
                    $boletimMMFertDAO->insBoletimMMFechado($bol, $this->base);
                    $idBolBD = $boletimMMFertDAO->idBoletimMM($bol, $this->base);
                } else {
                    $idBolBD = $boletimMMFertDAO->idBoletimMM($bol, $this->base);
                    $boletimMMFertDAO->updateBoletimMMFechado($idBolBD, $bol, $this->base);
                }
                $this->salvarApontMM($idBolBD, $bol->idBolMMFert, $dadosApont, $dadosImplemento);
                $this->salvarMovLeiraMM($idBolBD, $bol->idBolMMFert, $dadosMovLeira);
                $this->salvarRendMM($idBolBD, $bol->idBolMMFert, $dadosRendimento);
                $apontMMFertDAO = new ApontMMFertDAO();
                $qtdeApontBolMMFert = $apontMMFertDAO->verifQtdeApontMM($idBolBD, $this->base);
            }
            else{
                $v = $boletimMMFertDAO->verifBoletimFert($bol, $this->base);
                if ($v == 0) {
                    $boletimMMFertDAO->insBoletimFertFechado($bol, $this->base);
                    $idBolBD = $boletimMMFertDAO->idBoletimFert($bol, $this->base);
                } else {
                    $idBolBD = $boletimMMFertDAO->idBoletimFert($bol, $this->base);
                    $boletimMMFertDAO->updateBoletimFertFechado($idBolBD, $bol, $this->base);
                }
                $this->salvarApontFert($idBolBD, $bol->idBolMMFert, $dadosApont);
                $this->salvarRecolhFert($idBolBD, $bol->idBolMMFert, $dadosRecolhimento);
                $apontMMFertDAO = new ApontMMFertDAO();
                $qtdeApontBolMMFert = $apontMMFertDAO->verifQtdeApontFert($idBolBD, $this->base);
            }
            $idBolMMArray[] = array("idBolMMFert" => $bol->idBolMMFert, "qtdeApontBolMMFert" => $qtdeApontBolMMFert);
        }
        
        $dadoBol = array("boletim"=>$idBolMMArray);
        $retBol = json_encode($dadoBol);
        return 'BOLFECHADOMM_' . $retBol;
        
    }

    private function salvarApontMM($idBolBD, $idBolCel, $dadosAponta, $dadosImplemento) {
        $apontMMFertDAO = new ApontMMFertDAO;
        $boletimMMFertDAO = new BoletimMMFertDAO();
        $idApontArray = array();
        foreach ($dadosAponta as $apont) {
            if ($idBolCel == $apont->idBolMMFert) {
                $v = $apontMMFertDAO->verifApontMM($idBolBD, $apont, $this->base);
                if ($v == 0) {
                    $apontMMFertDAO->insApontMM($idBolBD, $apont, $this->base);
                }
                if($apont->osApontMMFert > 0){
                    $boletimMMFertDAO->updateBoletimMMOSAtiv($idBolBD, $apont, $this->base);
                    $apontMMFertDAO->updateApontMMOSAtiv($idBolBD, $apont, $this->base);
                }
                $idApontBD = $apontMMFertDAO->idApontMM($idBolBD, $apont, $this->base);
                $this->salvarImplMM($idApontBD, $apont->idApontMMFert, $dadosImplemento);
                $idApontArray[] = array("idApontMMFert" => $apont->idApontMMFert);
            }
        }
        $dadoApont = array("apont"=>$idApontArray);
        $retApont = json_encode($dadoApont);
        return $retApont;
    }
    
    
    
    private function salvarApontFert($idBolBD, $idBolCel, $dadosAponta) {
        $apontMMFertDAO = new ApontMMFertDAO();
        $idApontArray = array();
        foreach ($dadosAponta as $apont) {
            if ($idBolCel == $apont->idBolMMFert) {
                $v = $apontMMFertDAO->verifApontFert($idBolBD, $apont, $this->base);
                if ($v == 0) {
                    $apontMMFertDAO->insApontFert($idBolBD, $apont, $this->base);
                }
                $idApontArray[] = array("idApontMMFert" => $apont->idApontMMFert);
            }
        }
        $dadoApont = array("apont"=>$idApontArray);
        $retApont = json_encode($dadoApont);
        return $retApont;
    }

    private function salvarImplMM($idApontaBD, $idApontaCel, $dadosImplemento) {
        $implementoMMDAO = new ImplementoMMDAO();
        foreach ($dadosImplemento as $imp) {
            if ($idApontaCel == $imp->idApontMMFert) {
                $v = $implementoMMDAO->verifImplementoMM($idApontaBD, $imp, $this->base);
                if ($v == 0) {
                    $implementoMMDAO->insImplementoMM($idApontaBD, $imp, $this->base);
                }
            }
        }
    }

    private function salvarMovLeiraMM($idBolBD, $idBolCel, $dadosMovLeira) {
        $leiraDAO = new LeiraDAO;
        $idMovLeiraArray = array();
        foreach ($dadosMovLeira as $movleira) {
            if ($idBolCel == $movleira->idBolMMFert) {
                $v = $leiraDAO->verifMovLeiraMM($idBolBD, $movleira, $this->base);
                if ($v == 0) {
                    $leiraDAO->insMovLeiraMM($idBolBD, $movleira, $this->base);
                }
                $idMovLeiraArray[] = array("idMovLeira" => $movleira->idMovLeira);
            }
        }
        $dadoMovLeira = array("movleira"=>$idMovLeiraArray);
        $retMovLeira = json_encode($dadoMovLeira);
        return $retMovLeira;
    }
    
    private function salvarRendMM($idBolBD, $idBolCel, $dadosRendimento) {
        $rendimentoMMDAO = new RendimentoMMDAO();
        foreach ($dadosRendimento as $rend) {
            if ($idBolCel == $rend->idBolMMFert) {
                $v = $rendimentoMMDAO->verifRendimentoMM($idBolBD, $rend, $this->base);
                if ($v == 0) {
                    $rendimentoMMDAO->insRendimentoMM($idBolBD, $rend, $this->base);
                }
            }
        }
    }
    
    private function salvarRecolhFert($idBolBD, $idBolCel, $dadosRecolhimento) {
        $recolhimentoFertDAO = new RecolhimentoFertDAO();
        foreach ($dadosRecolhimento as $rend) {
            if ($idBolCel == $rend->idBolMMFert) {
                $v = $recolhimentoFertDAO->verifRecolhimentoFert($idBolBD, $rend, $this->base);
                if ($v == 0) {
                    $recolhimentoFertDAO->insRecolhimentoFert($idBolBD, $rend, $this->base);
                }
            }
        }
    }
    
    public function dados($versao) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 2.00){
        
            $motoMecDAO = new MotoMecDAO();

            $dados = array("dados" => $motoMecDAO->dados($this->base));
            $json_str = json_encode($dados);

            return $json_str;
        
        }
        
    }
    
}
