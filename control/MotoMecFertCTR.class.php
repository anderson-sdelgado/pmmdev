<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
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
require_once('../model/CarregDAO.class.php');
/**
 * Description of MotoMecFert
 *
 * @author anderson
 */
class MotoMecFertCTR {
    
    public function salvarBolAbertoMMFert($info) {

        $dados = $info['dado'];
        $array = explode("_",$dados);

        $jsonObjBoletim = json_decode($array[0]);
        $jsonObjApont = json_decode($array[1]);
        $jsonObjImplemento = json_decode($array[2]);
        $jsonObjMovLeira = json_decode($array[3]);
        $jsonObjApontMecan = json_decode($array[4]);
        $jsonObjBoletimPneu = json_decode($array[5]);
        $jsonObjItemMedPneu = json_decode($array[6]);
        $jsonObjCarreg = json_decode($array[7]);

        $dadosBoletim = $jsonObjBoletim->boletim;
        $dadosApont = $jsonObjApont->apont;
        $dadosImplemento = $jsonObjImplemento->implemento;
        $dadosMovLeira = $jsonObjMovLeira->movleira;
        $dadosApontMecan = $jsonObjApontMecan->apontmecan;
        $dadosBoletimPneu = $jsonObjBoletimPneu->boletimpneu;
        $dadosItemMedPneu = $jsonObjItemMedPneu->itemmedpneu;
        $dadosCarreg = $jsonObjCarreg->carreg;

        $ret = $this->salvarBoletimAbertoMMFert($dadosBoletim, $dadosApont, $dadosImplemento, $dadosMovLeira, $dadosApontMecan, $dadosBoletimPneu, $dadosItemMedPneu, $dadosCarreg);

        return $ret;

    }
    
    public function salvarBolFechadoMMFert($info) {

        $dados = $info['dado'];
        $array = explode("_",$dados);

        $jsonObjBoletim = json_decode($array[0]);
        $jsonObjApont = json_decode($array[1]);
        $jsonObjImpl = json_decode($array[2]);
        $jsonObjMovLeira = json_decode($array[3]);
        $jsonObjApontMecan = json_decode($array[4]);
        $jsonObjBoletimPneu = json_decode($array[5]);
        $jsonObjItemMedPneu = json_decode($array[6]);
        $jsonObjCarreg = json_decode($array[7]);
        $jsonObjRend = json_decode($array[8]);
        $jsonObjRecolh = json_decode($array[9]);

        $dadosBoletim = $jsonObjBoletim->boletim;
        $dadosApont = $jsonObjApont->apont;
        $dadosImplemento = $jsonObjImpl->implemento;
        $dadosMovLeira = $jsonObjMovLeira->movleira;
        $dadosApontMecan = $jsonObjApontMecan->apontmecan;
        $dadosBoletimPneu = $jsonObjBoletimPneu->boletimpneu;
        $dadosItemMedPneu = $jsonObjItemMedPneu->itemmedpneu;
        $dadosCarreg = $jsonObjCarreg->carreg;
        $dadosRendimento = $jsonObjRend->rendimento;
        $dadosRecolhimento = $jsonObjRecolh->recolhimento;

        $ret = $this->salvarBoletimFechMMFert($dadosBoletim, $dadosApont, $dadosImplemento, $dadosMovLeira, $dadosApontMecan, $dadosBoletimPneu, $dadosItemMedPneu, $dadosCarreg, $dadosRendimento, $dadosRecolhimento);

        return $ret;

    }

    private function salvarBoletimAbertoMMFert($dadosBoletim, $dadosApont, $dadosImplemento, $dadosMovLeira, $dadosApontMecan, $dadosBoletimPneu, $dadosItemMedPneu, $dadosCarreg) {
        
        $boletimMMFertDAO = new BoletimMMFertDAO();
        $idBolMMArray = array();
        
        foreach ($dadosBoletim as $bol) {
            if($bol->tipoBolMMFert === 1){
                $v = $boletimMMFertDAO->verifBoletimMM($bol);
                if ($v == 0) {
                    $boletimMMFertDAO->insBoletimMMAberto($bol);
                }
                $idBolBD = $boletimMMFertDAO->idBoletimMM($bol);
                $retApont = $this->salvarApontMM($idBolBD, $bol->idBolMMFert, $dadosApont, $dadosImplemento, $dadosBoletimPneu, $dadosItemMedPneu, $dadosCarreg);
                $retMovLeira = $this->salvarMovLeiraMM($idBolBD, $bol->idBolMMFert, $dadosMovLeira);
                $retApontMecan = $this->salvarApontMecan($idBolBD, $bol->idBolMMFert, $dadosApontMecan);
            }
            else{
                $v = $boletimMMFertDAO->verifBoletimFert($bol);
                if ($v == 0) {
                    $boletimMMFertDAO->insBoletimFertAberto($bol);
                }
                $idBolBD = $boletimMMFertDAO->idBoletimFert($bol);
                $retApont = $this->salvarApontFert($idBolBD, $bol->idBolMMFert, $dadosApont, $dadosImplemento, $dadosBoletimPneu, $dadosItemMedPneu, $dadosCarreg);
                $retMovLeira = $this->salvarMovLeiraMM($idBolBD, $bol->idBolMMFert, $dadosMovLeira);
                $retApontMecan = $this->salvarApontMecan($idBolBD, $bol->idBolMMFert, $dadosApontMecan);
            }
            $idBolMMArray[] = array("idBolMMFert" => $bol->idBolMMFert, "idExtBolMMFert" => $idBolBD);
        }
        
        $dadoBol = array("boletim"=>$idBolMMArray);
        $retBol = json_encode($dadoBol);
        return 'BOLABERTOMM_' . $retBol . "_" . $retApont . "_" . $retMovLeira . "_" . $retApontMecan;
        
    }

    private function salvarBoletimFechMMFert($dadosBoletim, $dadosApont, $dadosImplemento, $dadosMovLeira, $dadosApontMecan, $dadosBoletimPneu, $dadosItemMedPneu, $dadosCarreg, $dadosRendimento, $dadosRecolhimento) {
        
        $boletimMMFertDAO = new BoletimMMFertDAO();
        $idBolMMArray = array();
        
        foreach ($dadosBoletim as $bol) {
            if($bol->tipoBolMMFert === 1){
                $v = $boletimMMFertDAO->verifBoletimMM($bol);
                if ($v == 0) {
                    $boletimMMFertDAO->insBoletimMMFechado($bol);
                    $idBolBD = $boletimMMFertDAO->idBoletimMM($bol);
                } else {
                    $idBolBD = $boletimMMFertDAO->idBoletimMM($bol);
                    $boletimMMFertDAO->updateBoletimMMFechado($idBolBD, $bol);
                }
                $retApont = $this->salvarApontMM($idBolBD, $bol->idBolMMFert, $dadosApont, $dadosImplemento, $dadosBoletimPneu, $dadosItemMedPneu, $dadosCarreg);
                $retMovLeira = $this->salvarMovLeiraMM($idBolBD, $bol->idBolMMFert, $dadosMovLeira);
                $retApontMecan = $this->salvarApontMecan($idBolBD, $bol->idBolMMFert, $dadosApontMecan);
                $this->salvarRendMM($idBolBD, $bol->idBolMMFert, $dadosRendimento);
            }
            else{
                $v = $boletimMMFertDAO->verifBoletimFert($bol);
                if ($v == 0) {
                    $boletimMMFertDAO->insBoletimFertFechado($bol);
                    $idBolBD = $boletimMMFertDAO->idBoletimFert($bol);
                } else {
                    $idBolBD = $boletimMMFertDAO->idBoletimFert($bol);
                    $boletimMMFertDAO->updateBoletimFertFechado($idBolBD, $bol);
                }
                $retApont = $this->salvarApontFert($idBolBD, $bol->idBolMMFert, $dadosApont, $dadosImplemento, $dadosBoletimPneu, $dadosItemMedPneu, $dadosCarreg);
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

    private function salvarApontMM($idBolBD, $idBolCel, $dadosApont, $dadosImplemento, $dadosBoletimPneu, $dadosItemMedPneu, $dadosCarreg) {
        
        $apontMMFertDAO = new ApontMMFertDAO;
        $boletimMMFertDAO = new BoletimMMFertDAO();
        
        $idApontArray = array();
        $idApontImplArray = array();
        $idBolPneuArray = array();
        $idCarregArray = array();
        
        foreach ($dadosApont as $apont) {
            if ($idBolCel == $apont->idBolMMFert) {
                $v = $apontMMFertDAO->verifApontMM($idBolBD, $apont);
                if ($v == 0) {
                    $apontMMFertDAO->insApontMM($idBolBD, $apont);
                }
                if($apont->osApontMMFert > 0){
                    $boletimMMFertDAO->updateBoletimMMOSAtiv($idBolBD, $apont);
                    $apontMMFertDAO->updateApontMMOSAtiv($idBolBD, $apont);
                }
                $idApontBD = $apontMMFertDAO->idApontMM($idBolBD, $apont);
                $idApontImplArray = $this->salvarImplMM($idApontBD, $apont->idApontMMFert, $dadosImplemento, $idApontImplArray);
                $idBolPneuArray = $this->salvarBoletimPneu($idApontBD, $apont->idApontMMFert, $dadosBoletimPneu, $dadosItemMedPneu, 1, $idBolPneuArray);
                $idCarregArray = $this->salvarCarreg($idApontBD, $apont->idApontMMFert, $dadosCarreg, $idCarregArray);
                $idApontArray[] = array("idApontMMFert" => $apont->idApontMMFert);
            }
        }
        
        $dadoApont = array("apont"=>$idApontArray);
        $retApont = json_encode($dadoApont);
        
        $dadoApontImpl = array("apontimpl"=>$idApontImplArray);
        $retApontImpl = json_encode($dadoApontImpl);
        
        $dadoBolPneu = array("boletimpneu"=>$idBolPneuArray);
        $retBolPneu = json_encode($dadoBolPneu);
        
        $dadoCarreg = array("carreg"=>$idCarregArray);
        $retCarreg = json_encode($dadoCarreg);
        
        return $retApont . "_" . $retApontImpl . "_" . $retBolPneu . "_" . $retCarreg;
    }

    private function salvarApontFert($idBolBD, $idBolCel, $dadosApont, $dadosImplemento, $dadosBoletimPneu, $dadosItemMedPneu, $dadosCarreg) {
        
        $apontMMFertDAO = new ApontMMFertDAO();
        
        $idApontArray = array();
        $idApontImplArray = array();
        $idBolPneuArray = array();
        $idCarregArray = array();
        
        foreach ($dadosApont as $apont) {
            if ($idBolCel == $apont->idBolMMFert) {
                $v = $apontMMFertDAO->verifApontFert($idBolBD, $apont);
                if ($v == 0) {
                    $apontMMFertDAO->insApontFert($idBolBD, $apont);
                }
                $idApontBD = $apontMMFertDAO->idApontFert($idBolBD, $apont);
                $idApontImplArray = $this->salvarImplMM($idApontBD, $apont->idApontMMFert, $dadosImplemento, $idApontImplArray);
                $idBolPneuArray = $this->salvarBoletimPneu($idApontBD, $apont->idApontMMFert, $dadosBoletimPneu, $dadosItemMedPneu, 2, $idBolPneuArray);
                $idCarregArray = $this->salvarCarreg($idApontBD, $apont->idApontMMFert, $dadosCarreg, $idCarregArray);
                $idApontArray[] = array("idApontMMFert" => $apont->idApontMMFert);
            }
        }
        
        $dadoApont = array("apont"=>$idApontArray);
        $retApont = json_encode($dadoApont);
        
        $dadoApontImpl = array("apontimpl"=>$idApontImplArray);
        $retApontImpl = json_encode($dadoApontImpl);
        
        $dadoBolPneu = array("boletimpneu"=>$idBolPneuArray);
        $retBolPneu = json_encode($dadoBolPneu);
        
        $dadoCarreg = array("carreg"=>$idCarregArray);
        $retCarreg = json_encode($dadoCarreg);
        
        return $retApont . "_" . $retApontImpl . "_" . $retBolPneu . "_" . $retCarreg;
    }
        
    private function salvarApontMecan($idBolBD, $idBolCel, $dadosApontMecan) {
        $apontMecanDAO = new ApontMecanDAO();
        $idApontMecanArray = array();
        foreach ($dadosApontMecan as $apontMecan) {
            if ($idBolCel == $apontMecan->idBolApontMecan) {
                $v = $apontMecanDAO->verifApontMecan($idBolBD, $apontMecan);
                if ($v == 0) {
                    if($apontMecan->statusApontMecan == 1){
                        $apontMecanDAO->insApontMecanAberto($idBolBD, $apontMecan);
                    }
                    else if($apontMecan->statusApontMecan == 3){
                        $apontMecanDAO->insApontMecanFechado($idBolBD, $apontMecan);
                    }
                }
                else{
                    if($apontMecan->statusApontMecan == 3){
                        $apontMecanDAO->updateApontMecan($idBolBD, $apontMecan);
                    }
                }
                $idApontMecanArray[] = array("idApontMecan" => $apontMecan->idApontMecan);
            }
        }
        $dadoApontMecan = array("apontmecan"=>$idApontMecanArray);
        $retApontMecan = json_encode($dadoApontMecan);
        return $retApontMecan;
    }
    
    private function salvarImplMM($idApontaBD, $idApontaCel, $dadosImplemento, $idApontImplArray) {
        $implementoMMDAO = new ImplementoMMDAO();
        foreach ($dadosImplemento as $imp) {
            if ($idApontaCel == $imp->idApontMMFert) {
                $v = $implementoMMDAO->verifImplementoMM($idApontaBD, $imp);
                if ($v == 0) {
                    $implementoMMDAO->insImplementoMM($idApontaBD, $imp);
                }
                $idApontImplArray[] = array("idApontImplMM" => $imp->idApontImplMM);
            }
        }
        return $idApontImplArray;
    }

    private function salvarBoletimPneu($idApontBD, $idApontCel, $dadosBolPneu, $dadosItemPneu, $tipoAplic, $idBolPneuArray) {
        $boletimPneuDAO = new BoletimPneuDAO();
        foreach ($dadosBolPneu as $bolPneu) {
            if ($idApontCel == $bolPneu->idApontBolPneu) {
                $v = $boletimPneuDAO->verifBoletimPneu($idApontBD, $bolPneu);
                if ($v == 0) {
                    $boletimPneuDAO->insBoletimPneu($idApontBD, $bolPneu, $tipoAplic);
                    $idBolPneu = $boletimPneuDAO->idBoletimPneu($idApontBD, $bolPneu);
                    $this->salvarItemMedPneu($idBolPneu, $bolPneu->idBolPneu, $dadosItemPneu);
                }
                $idBolPneuArray[] = array("idBolPneu" => $bolPneu->idBolPneu);
            }
        }
        return $idBolPneuArray;
    }

    private function salvarItemMedPneu($idBolPneuBD, $idBolPneuCel, $dadosItemPneu) {
        $itemMedPneuDAO = new ItemMedPneuDAO();
        foreach ($dadosItemPneu as $itemPneu) {
            if ($idBolPneuCel == $itemPneu->idBolItemCalibPneu) {
                $v = $itemMedPneuDAO->verifItemMedPneu($idBolPneuBD, $itemPneu);
                if ($v == 0) {
                    $itemMedPneuDAO->insItemMedPneu($idBolPneuBD, $itemPneu);
                }
            }
        }
    }
    
    private function salvarCarreg($idApontBD, $idApontCel, $dadosCarreg, $idCarregArray) {
        $carregDAO = new CarregDAO();
        $tipo = 0;
        foreach ($dadosCarreg as $carreg) {
            if ($idApontCel == $carreg->idApontCarreg) {
                $tipo = $carreg->tipoCarreg;
                if ($carreg->tipoCarreg == 1) {
                    $carregDAO->updDescarregProd($idApontBD, $carreg);
                } else {
                    $v = $carregDAO->verifCarregComp($carreg);
                    $carregDAO->cancelCarregComp($carreg);
                    if ($v == 0) {
                        $carregDAO->insCarregComp($idApontBD, $carreg);
                    }
                }
                $idCarregArray[] = array("idCarreg" => $carreg->idCarreg);
            }
        }
        return $idCarregArray;
    }
    
    private function salvarMovLeiraMM($idBolBD, $idBolCel, $dadosMovLeira) {
        $leiraDAO = new LeiraDAO;
        $idMovLeiraArray = array();
        foreach ($dadosMovLeira as $movleira) {
            if ($idBolCel == $movleira->idBolMMFert) {
                $v = $leiraDAO->verifMovLeiraMM($idBolBD, $movleira);
                if ($v == 0) {
                    $leiraDAO->insMovLeiraMM($idBolBD, $movleira);
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
                $v = $rendimentoMMDAO->verifRendimentoMM($idBolBD, $rend);
                if ($v == 0) {
                    $rendimentoMMDAO->insRendimentoMM($idBolBD, $rend);
                }
            }
        }
    }
    
    private function salvarRecolhFert($idBolBD, $idBolCel, $dadosRecolhimento) {
        $recolhimentoFertDAO = new RecolhimentoFertDAO();
        foreach ($dadosRecolhimento as $rend) {
            if ($idBolCel == $rend->idBolMMFert) {
                $v = $recolhimentoFertDAO->verifRecolhimentoFert($idBolBD, $rend);
                if ($v == 0) {
                    $recolhimentoFertDAO->insRecolhimentoFert($idBolBD, $rend);
                }
            }
        }
    }
    
    public function dados() {

        $motoMecDAO = new MotoMecDAO();

        $dados = array("dados" => $motoMecDAO->dados());
        $json_str = json_encode($dados);

        return $json_str;

    }
    
}
