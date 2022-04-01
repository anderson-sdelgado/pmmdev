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
require_once('../model/BoletimPneuDAO.class.php');
require_once('../model/ItemMedPneuDAO.class.php');
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
            $jsonObjBoletimPneu = json_decode($array[5]);
            $jsonObjItemMedPneu = json_decode($array[6]);

            $dadosBoletim = $jsonObjBoletim->boletim;
            $dadosApont = $jsonObjApont->apont;
            $dadosImplemento = $jsonObjImplemento->implemento;
            $dadosMovLeira = $jsonObjMovLeira->movleira;
            $dadosApontMecan = $jsonObjApontMecan->apontmecan;
            $dadosBoletimPneu = $jsonObjBoletimPneu->boletimpneu;
            $dadosItemMedPneu = $jsonObjItemMedPneu->itemmedpneu;

            $ret = $this->salvarBoletimAbertoMMFert($dadosBoletim, $dadosApont, $dadosImplemento, $dadosMovLeira, $dadosApontMecan, $dadosBoletimPneu, $dadosItemMedPneu);

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
            $jsonObjBoletimPneu = json_decode($array[5]);
            $jsonObjItemMedPneu = json_decode($array[6]);
            $jsonObjRend = json_decode($array[7]);
            $jsonObjRecolh = json_decode($array[8]);
            
            $dadosBoletim = $jsonObjBoletim->boletim;
            $dadosApont = $jsonObjApont->apont;
            $dadosImplemento = $jsonObjImpl->implemento;
            $dadosMovLeira = $jsonObjMovLeira->movleira;
            $dadosApontMecan = $jsonObjApontMecan->apontmecan;
            $dadosBoletimPneu = $jsonObjBoletimPneu->boletimpneu;
            $dadosItemMedPneu = $jsonObjItemMedPneu->itemmedpneu;
            $dadosRendimento = $jsonObjRend->rendimento;
            $dadosRecolhimento = $jsonObjRecolh->recolhimento;

            $ret = $this->salvarBoletimFechMMFert($dadosBoletim, $dadosApont, $dadosImplemento, $dadosMovLeira, $dadosApontMecan, $dadosBoletimPneu, $dadosItemMedPneu, $dadosRendimento, $dadosRecolhimento);

            return $ret;
        }
    }
    
    private function salvarLog($dados, $pagina, $versao) {
        $logEnvioDAO = new LogEnvioDAO();
        $logEnvioDAO->salvarDados($dados, $pagina, $versao, $this->base);
    }

    private function salvarBoletimAbertoMMFert($dadosBoletim, $dadosApont, $dadosImplemento, $dadosMovLeira, $dadosApontMecan, $dadosBoletimPneu, $dadosItemMedPneu) {
        
        $boletimMMFertDAO = new BoletimMMFertDAO();
        $idBolMMArray = array();
        
        foreach ($dadosBoletim as $bol) {
            if($bol->tipoBolMMFert === 1){
                $v = $boletimMMFertDAO->verifBoletimMM($bol, $this->base);
                if ($v == 0) {
                    $boletimMMFertDAO->insBoletimMMAberto($bol, $this->base);
                }
                $idBolBD = $boletimMMFertDAO->idBoletimMM($bol, $this->base);
                $retApont = $this->salvarApontMM($idBolBD, $bol->idBolMMFert, $dadosApont, $dadosImplemento, $dadosBoletimPneu, $dadosItemMedPneu);
                $retMovLeira = $this->salvarMovLeiraMM($idBolBD, $bol->idBolMMFert, $dadosMovLeira);
                $retApontMecan = $this->salvarApontMecan($idBolBD, $bol->idBolMMFert, $dadosApontMecan);
            }
            else{
                $v = $boletimMMFertDAO->verifBoletimFert($bol, $this->base);
                if ($v == 0) {
                    $boletimMMFertDAO->insBoletimFertAberto($bol, $this->base);
                }
                $idBolBD = $boletimMMFertDAO->idBoletimFert($bol, $this->base);
                $retApont = $this->salvarApontFert($idBolBD, $bol->idBolMMFert, $dadosApont, $dadosBoletimPneu, $dadosItemMedPneu);
                $retMovLeira = $this->salvarMovLeiraMM($idBolBD, $bol->idBolMMFert, $dadosMovLeira);
                $retApontMecan = $this->salvarApontMecan($idBolBD, $bol->idBolMMFert, $dadosApontMecan);
            }
            $idBolMMArray[] = array("idBolMMFert" => $bol->idBolMMFert, "idExtBolMMFert" => $idBolBD);
        }
        
        $dadoBol = array("boletim"=>$idBolMMArray);
        $retBol = json_encode($dadoBol);
        return 'BOLABERTOMM_' . $retBol . "_" . $retApont . "_" . $retMovLeira . "_" . $retApontMecan;
        
    }

    private function salvarBoletimFechMMFert($dadosBoletim, $dadosApont, $dadosImplemento, $dadosMovLeira, $dadosApontMecan, $dadosBoletimPneu, $dadosItemMedPneu, $dadosRendimento, $dadosRecolhimento) {
        
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
                $retApont = $this->salvarApontMM($idBolBD, $bol->idBolMMFert, $dadosApont, $dadosImplemento, $dadosBoletimPneu, $dadosItemMedPneu);
                $retMovLeira = $this->salvarMovLeiraMM($idBolBD, $bol->idBolMMFert, $dadosMovLeira);
                $retApontMecan = $this->salvarApontMecan($idBolBD, $bol->idBolMMFert, $dadosApontMecan);
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
                $retApont = $this->salvarApontFert($idBolBD, $bol->idBolMMFert, $dadosApont, $dadosBoletimPneu, $dadosItemMedPneu);
                $retMovLeira = $this->salvarMovLeiraMM($idBolBD, $bol->idBolMMFert, $dadosMovLeira);
                $retApontMecan = $this->salvarApontMecan($idBolBD, $bol->idBolMMFert, $dadosApontMecan);
                $this->salvarRecolhFert($idBolBD, $bol->idBolMMFert, $dadosRecolhimento);
            }
            $idBolMMArray[] = array("idBolMMFert" => $bol->idBolMMFert);
        }
        
        $dadoBol = array("boletim"=>$idBolMMArray);
        $retBol = json_encode($dadoBol);
        return 'BOLFECHADOMM_' . $retBol . "_" . $retApont . "_" . $retMovLeira . "_" . $retApontMecan;
        
    }

    private function salvarApontMM($idBolBD, $idBolCel, $dadosApont, $dadosImplemento, $dadosBoletimPneu, $dadosItemMedPneu) {
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
                $retApontImpl = $this->salvarImplMM($idApontBD, $apont->idApontMMFert, $dadosImplemento);
                $retBolPneu = $this->salvarBoletimPneu($idApontBD, $apont->idApontMMFert, $dadosBoletimPneu, $dadosItemMedPneu, 1);
                $idApontArray[] = array("idApontMMFert" => $apont->idApontMMFert);
            }
        }
        $dadoApont = array("apont"=>$idApontArray);
        $retApont = json_encode($dadoApont);
        return $retApont . "_" . $retApontImpl . "_" . $retBolPneu;
    }

    private function salvarApontFert($idBolBD, $idBolCel, $dadosApon, $dadosBoletimPneu, $dadosItemMedPneu) {
        $apontMMFertDAO = new ApontMMFertDAO();
        $idApontArray = array();
        foreach ($dadosApont as $apont) {
            if ($idBolCel == $apont->idBolMMFert) {
                $v = $apontMMFertDAO->verifApontFert($idBolBD, $apont, $this->base);
                if ($v == 0) {
                    $apontMMFertDAO->insApontFert($idBolBD, $apont, $this->base);
                }
                $idApontBD = $apontMMFertDAO->idApontFert($idBolBD, $apont, $this->base);
                $retBolPneu = $this->salvarBoletimPneu($idApontBD, $apont->idApontMMFert, $dadosBoletimPneu, $dadosItemMedPneu, 2);
                $idApontArray[] = array("idApontMMFert" => $apont->idApontMMFert);
            }
        }
        $dadoApont = array("apont"=>$idApontArray);
        $retApont = json_encode($dadoApont);
        return $retApont . "_" . $retBolPneu;
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
        $idApontImplArray = array();
        foreach ($dadosImplemento as $imp) {
            if ($idApontaCel == $imp->idApontMMFert) {
                $v = $implementoMMDAO->verifImplementoMM($idApontaBD, $imp, $this->base);
                if ($v == 0) {
                    $implementoMMDAO->insImplementoMM($idApontaBD, $imp, $this->base);
                }
                $idApontImplArray[] = array("idApontImplMM" => $imp->idApontImplMM);
            }
        }
        $dadoApontImpl = array("apontimpl"=>$idApontImplArray);
        $retApontImpl = json_encode($dadoApontImpl);
        return $retApontImpl;
    }

    private function salvarBoletimPneu($idApontBD, $idApontCel, $dadosBolPneu, $dadosItemPneu, $tipoAplic) {
        $boletimPneuDAO = new BoletimPneuDAO();
        $idBolPneuArray = array();
        foreach ($dadosBolPneu as $bolPneu) {
            if ($idApontCel == $bolPneu->idApontBolPneu) {
                $v = $boletimPneuDAO->verifBoletimPneu($idApontBD, $bolPneu, $this->base);
                if ($v == 0) {
                    $boletimPneuDAO->insBoletimPneu($idApontBD, $bolPneu, $tipoAplic, $this->base);
                    $idBolPneu = $boletimPneuDAO->idBoletimPneu($idApontBD, $bolPneu, $this->base);
                    $this->salvarItemMedPneu($idBolPneu, $bolPneu->idBolPneu, $dadosItemPneu);
                }
                $idBolPneuArray[] = array("idBolPneu" => $bolPneu->idBolPneu);
            }
        }
        $dadoBolPneu = array("boletimpneu"=>$idBolPneuArray);
        $retBolPneu = json_encode($dadoBolPneu);
        return $retBolPneu;
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
