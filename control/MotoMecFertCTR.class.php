<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/LogEnvioDAO.class.php');
require_once('../model/BoletimMMFertDAO.class.php');
require_once('../model/ApontMMFertDAO.class.php');
require_once('../model/ApontMecanDAO.class.php');
require_once('../model/ImplementoMMDAO.class.php');
require_once('../model/LeiraDAO.class.php');
require_once('../model/RendimentoMMDAO.class.php');
require_once('../model/RecolhimentoFertDAO.class.php');
require_once('../model/MotoMecDAO.class.php');
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

            $array = explode("_",$dados);
            
            $jsonObjBoletim = json_decode($array[0]);
            $jsonObjApont = json_decode($array[1]);
            $jsonObjImplemento = json_decode($array[2]);
            $jsonObjMovLeira = json_decode($array[3]);
            $jsonObjApontMecan = json_decode($array[4]);

            $dadosBoletim = $jsonObjBoletim->boletim;
            $dadosApont = $jsonObjApont->apont;
            $dadosImplemento = $jsonObjImplemento->implemento;
            $dadosMovLeira = $jsonObjMovLeira->movleira;
            $dadosApontMecan = $jsonObjApontMecan->apontmecan;

            $ret = $this->salvarBoletimAbertoMMFert($dadosBoletim, $dadosApont, $dadosImplemento, $dadosMovLeira, $dadosApontMecan);

            return $ret;
        }
    }
    
    public function salvarBolFechadoMMFert($versao, $info, $pagina) {

        $dados = $info['dado'];
        $this->salvarLog($dados, $pagina, $versao);
        $versao = str_replace("_", ".", $versao);

        if ($versao >= 2.00) {

            $array = explode("_",$dados);
            
            $jsonObjBoletim = json_decode($array[0]);
            $jsonObjApont = json_decode($array[1]);
            $jsonObjImpl = json_decode($array[2]);
            $jsonObjMovLeira = json_decode($array[3]);
            $jsonObjApontMecan = json_decode($array[4]);
            $jsonObjRend = json_decode($array[5]);
            $jsonObjRecolh = json_decode($array[6]);
            
            $dadosBoletim = $jsonObjBoletim->boletim;
            $dadosApont = $jsonObjApont->apont;
            $dadosImplemento = $jsonObjImpl->implemento;
            $dadosMovLeira = $jsonObjMovLeira->movleira;
            $dadosApontMecan = $jsonObjApontMecan->apontmecan;
            $dadosRendimento = $jsonObjRend->rendimento;
            $dadosRecolhimento = $jsonObjRecolh->recolhimento;

            $ret = $this->salvarBoletimFechMMFert($dadosBoletim, $dadosApont, $dadosImplemento, $dadosMovLeira, $dadosApontMecan, $dadosRendimento, $dadosRecolhimento);

            return $ret;
        }
    }
    
    private function salvarLog($dados, $pagina, $versao) {
        $logEnvioDAO = new LogEnvioDAO();
        $logEnvioDAO->salvarDados($dados, $pagina, $versao, $this->base);
    }

    private function salvarBoletimAbertoMMFert($dadosBoletim, $dadosApont, $dadosImplemento, $dadosMovLeira, $dadosApontMecan) {
        
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
                $retApontMecan = $this->salvarApontMecan($idBolBD, $bol->idBolMMFert, $dadosApontMecan);
            }
            else{
                $v = $boletimMMFertDAO->verifBoletimFert($bol, $this->base);
                if ($v == 0) {
                    $boletimMMFertDAO->insBoletimFertAberto($bol, $this->base);
                }
                $idBolBD = $boletimMMFertDAO->idBoletimFert($bol, $this->base);
                $retApont = $this->salvarApontFert($idBolBD, $bol->idBolMMFert, $dadosApont);
                $retMovLeira = $this->salvarMovLeiraMM($idBolBD, $bol->idBolMMFert, $dadosMovLeira);
                $retApontMecan = $this->salvarApontMecan($idBolBD, $bol->idBolMMFert, $dadosApontMecan);
            }
            $idBolMMArray[] = array("idBolMMFert" => $bol->idBolMMFert, "idExtBolMMFert" => $idBolBD);
        }
        
        $dadoBol = array("boletim"=>$idBolMMArray);
        $retBol = json_encode($dadoBol);
        return 'BOLABERTOMM_' . $retBol . "|" . $retApont . "#" . $retMovLeira . "?" . $retApontMecan;
        
    }

    private function salvarBoletimFechMMFert($dadosBoletim, $dadosApont, $dadosImplemento, $dadosMovLeira, $dadosApontMecan, $dadosRendimento, $dadosRecolhimento) {
        
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
                $this->salvarApontMecan($idBolBD, $bol->idBolMMFert, $dadosApontMecan);
                $this->salvarRendMM($idBolBD, $bol->idBolMMFert, $dadosRendimento);
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
                $this->salvarApontMecan($idBolBD, $bol->idBolMMFert, $dadosApontMecan);
                $this->salvarRecolhFert($idBolBD, $bol->idBolMMFert, $dadosRecolhimento);
            }
            $idBolMMArray[] = array("idBolMMFert" => $bol->idBolMMFert);
        }
        
        $dadoBol = array("boletim"=>$idBolMMArray);
        $retBol = json_encode($dadoBol);
        return 'BOLFECHADOMM_' . $retBol;
        
    }

    private function salvarApontMM($idBolBD, $idBolCel, $dadosApont, $dadosImplemento) {
        $apontMMFertDAO = new ApontMMFertDAO;
        $boletimMMFertDAO = new BoletimMMFertDAO();
        $idApontArray = array();
        foreach ($dadosApont as $apont) {
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

    private function salvarApontFert($idBolBD, $idBolCel, $dadosApont) {
        $apontMMFertDAO = new ApontMMFertDAO();
        $idApontArray = array();
        foreach ($dadosApont as $apont) {
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
        
    private function salvarApontMecan($idBolBD, $idBolCel, $dadosApontMecan) {
        $apontMecanDAO = new ApontMecanDAO();
        $idApontMecanArray = array();
        foreach ($dadosApontMecan as $apontMecan) {
            if ($idBolCel == $apontMecan->idBolApontMecan) {
                $v = $apontMecanDAO->verifApontMecan($idBolBD, $apontMecan, $this->base);
                if ($v == 0) {
                    if($apontMecan->statusApontMecan == 1){
                        $apontMecanDAO->insApontMecanAberto($idBolBD, $apontMecan, $this->base);
                    }
                    else{
                        $apontMecanDAO->insApontMecanFechado($idBolBD, $apontMecan, $this->base);
                    }
                }
                else{
                    if($apontMecan->statusApontMecan == 3){
                        $apontMecanDAO->updateApontMecan($idBolBD, $apontMecan, $this->base);
                    }
                }
                $idApontMecanArray[] = array("idApontMecan" => $apontMecan->idApontMecan);
            }
        }
        $dadoApontMecan = array("apontmecan"=>$idApontMecanArray);
        $retApontMecan = json_encode($dadoApontMecan);
        return $retApontMecan;
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
