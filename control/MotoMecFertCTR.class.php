<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../control/AtualAplicCTR.class.php');
require_once('../model/BoletimMMFertDAO.class.php');
require_once('../model/ApontMMFertDAO.class.php');
require_once('../model/ApontMecanDAO.class.php');
require_once('../model/ImplementoMMDAO.class.php');
require_once('../model/BoletimPneuDAO.class.php');
require_once('../model/ItemMedPneuDAO.class.php');
require_once('../model/ItemManutPneuDAO.class.php');
require_once('../model/LeiraDAO.class.php');
require_once('../model/RendimentoMMDAO.class.php');
require_once('../model/RecolhimentoFertDAO.class.php');
require_once('../model/MotoMecDAO.class.php');
require_once('../model/CarregDAO.class.php');
require_once('../model/CarregCanaDAO.class.php');
/**
 * Description of MotoMecFert
 *
 * @author anderson
 */
class MotoMecFertCTR {

    public function salvarBoletimMMFert($body){

        $boletimArray = json_decode($body);
        $this->salvarBoletim($boletimArray);
        return $body;

    }

    private function salvarBoletim($boletimArray) {
        
        $boletimMMFertDAO = new BoletimMMFertDAO();
        
        foreach ($boletimArray as $bol) {
            if($bol->tipoBolMMFert === 1){
                $v = $boletimMMFertDAO->verifBoletimMM($bol);
                if($bol->statusBolMMFert == 1){
                    if ($v == 0) {
                        $boletimMMFertDAO->insBoletimMMAberto($bol);
                    }
                    $idBolBD = $boletimMMFertDAO->idBoletimMM($bol);
                } else {
                    if ($v == 0) {
                        $boletimMMFertDAO->insBoletimMMFechado($bol);
                        $idBolBD = $boletimMMFertDAO->idBoletimMM($bol);
                    } else {
                        $idBolBD = $boletimMMFertDAO->idBoletimMM($bol);
                        $boletimMMFertDAO->updateBoletimMMFechado($idBolBD, $bol);
                    }
                }
                $this->salvarApontMM($idBolBD, $bol->idBolMMFert, $bol->apontMMFertList);
                $this->salvarBoletimPneu($idBolBD, $bol->idBolMMFert, $bol->boletimPneuList, 1);
                $this->salvarApontMecan($idBolBD, $bol->idBolMMFert, $bol->apontMecanList);
                $this->salvarRendMM($idBolBD, $bol->idBolMMFert, $bol->rendMMList);
            }
            else{
                $v = $boletimMMFertDAO->verifBoletimFert($bol);
                if($bol->statusBolMMFert == 1){
                    if ($v == 0) {
                        $boletimMMFertDAO->insBoletimFertAberto($bol);
                    }
                    $idBolBD = $boletimMMFertDAO->idBoletimFert($bol);
                } else {
                    if ($v == 0) {
                        $boletimMMFertDAO->insBoletimFertFechado($bol);
                        $idBolBD = $boletimMMFertDAO->idBoletimFert($bol);
                    } else {
                        $idBolBD = $boletimMMFertDAO->idBoletimFert($bol);
                        $boletimMMFertDAO->updateBoletimFertFechado($idBolBD, $bol);
                    }
                }
                $this->salvarApontFert($idBolBD, $bol->idBolMMFert, $bol->apontMMFertList);
                $this->salvarBoletimPneu($idBolBD, $bol->idBolMMFert, $bol->boletimPneuList, 2);
                $this->salvarApontMecan($idBolBD, $bol->idBolMMFert, $bol->apontMecanList);
                $this->salvarRecolhFert($idBolBD, $bol->idBolMMFert, $bol->recolhFertList);
            }
        }

    }

    private function salvarApontMM($idBolBD, $idBolCel, $apontArray) {
        $apontMMFertDAO = new ApontMMFertDAO;
        $boletimMMFertDAO = new BoletimMMFertDAO();
        foreach ($apontArray as $apont) {
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
                $this->salvarImplMM($idApontBD, $apont->idApontMMFert, $apont->apontImplMMList);
                $this->salvarCarreg($idApontBD, $apont->idApontMMFert, $apont->carregCompList);
            }
        }
        
    }

    private function salvarApontFert($idBolBD, $idBolCel, $apontArray) {
        $apontMMFertDAO = new ApontMMFertDAO();
        foreach ($apontArray as $apont) {
            if ($idBolCel == $apont->idBolMMFert) {
                $v = $apontMMFertDAO->verifApontFert($idBolBD, $apont);
                if ($v == 0) {
                    $apontMMFertDAO->insApontFert($idBolBD, $apont);
                }
                $idApontBD = $apontMMFertDAO->idApontFert($idBolBD, $apont);
                $this->salvarImplMM($idApontBD, $apont->idApontMMFert, $apont->apontImplMMList);
                $this->salvarCarreg($idApontBD, $apont->idApontMMFert, $apont->carregCompList);
            }
        }

    }
        
    private function salvarApontMecan($idBolBD, $idBolCel, $apontMecanArray) {
        $apontMecanDAO = new ApontMecanDAO();
        foreach ($apontMecanArray as $apontMecan) {
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
            }
        }

    }
    
    private function salvarImplMM($idApontaBD, $idApontaCel, $dadosImplemento) {
        $implementoMMDAO = new ImplementoMMDAO();
        foreach ($dadosImplemento as $imp) {
            if ($idApontaCel == $imp->idApontMMFert) {
                $v = $implementoMMDAO->verifImplementoMM($idApontaBD, $imp);
                if ($v == 0) {
                    $implementoMMDAO->insImplementoMM($idApontaBD, $imp);
                }
            }
        }
    }
    
    private function salvarBoletimPneu($idBolBD, $idBolCel, $bolPneuArray, $tipoAplic) {
        $boletimPneuDAO = new BoletimPneuDAO();
        foreach ($bolPneuArray as $bolPneu) {
            if ($idBolCel == $bolPneu->idBolMMFertPneu) {
                $v = $boletimPneuDAO->verifBoletimPneu($idBolBD, $bolPneu);
                if ($v == 0) {
                    $boletimPneuDAO->insBoletimPneu($idBolBD, $bolPneu, $tipoAplic);
                    $idBolPneu = $boletimPneuDAO->idBoletimPneu($idBolBD, $bolPneu);
                    $this->salvarItemMedPneu($idBolPneu, $bolPneu->idBolPneu, $bolPneu->itemCalibPneuList);
                    $this->salvarItemManutPneu($idBolPneu, $bolPneu->idBolPneu, $bolPneu->itemManutPneuBeanList);
                }
            }
        }
    }

    private function salvarItemMedPneu($idBolPneuBD, $idBolPneuCel, $dadosItemCalibPneu) {
        $itemMedPneuDAO = new ItemMedPneuDAO();
        foreach ($dadosItemCalibPneu as $itemCalibPneu) {
            if ($idBolPneuCel == $itemCalibPneu->idBolItemCalibPneu) {
                $v = $itemMedPneuDAO->verifItemMedPneu($idBolPneuBD, $itemCalibPneu);
                if ($v == 0) {
                    $itemMedPneuDAO->insItemMedPneu($idBolPneuBD, $itemCalibPneu);
                }
            }
        }
    }
    
    private function salvarItemManutPneu($idBolPneuBD, $idBolPneuCel, $dadosItemManutPneu) {
        $itemManutPneuDAO = new ItemManutPneuDAO();
        foreach ($dadosItemManutPneu as $itemManutPneu) {
            if ($idBolPneuCel == $itemManutPneu->idBolItemManutPneu) {
                $v = $itemManutPneuDAO->verifItemManutPneu($idBolPneuBD, $itemManutPneu);
                if ($v == 0) {
                    $itemManutPneuDAO->insItemManutPneu($idBolPneuBD, $itemManutPneu);
                }
            }
        }
    }
    
    private function salvarCarreg($idApontBD, $idApontCel, $carregArray) {
        $carregDAO = new CarregDAO();
        $tipo = 0;
        foreach ($carregArray as $carreg) {
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
            }
        }
    }
    
    private function salvarMovLeiraMM($idBolBD, $idBolCel, $dadosMovLeira) {
        $leiraDAO = new LeiraDAO;
        foreach ($dadosMovLeira as $movleira) {
            if ($idBolCel == $movleira->idBolMMFert) {
                $v = $leiraDAO->verifMovLeiraMM($idBolBD, $movleira);
                if ($v == 0) {
                    $leiraDAO->insMovLeiraMM($idBolBD, $movleira);
                }
            }
        }
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
        foreach ($dadosRecolhimento as $recolh) {
            if ($idBolCel == $recolh->idBolMMFert) {
                $v = $recolhimentoFertDAO->verifRecolhimentoFert($idBolBD, $recolh);
                if ($v == 0) {
                    $recolhimentoFertDAO->insRecolhimentoFert($idBolBD, $recolh);
                }
            }
        }
    }
    
    public function dados($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){
            
            $motoMecDAO = new MotoMecDAO();

            $dados = array("dados" => $motoMecDAO->dados());
            $json_str = json_encode($dados);

            return $json_str;
        
        }

    }

    public function pesqLocalCarreg($info) {
        
        $atualAplicDAO = new AtualAplicDAO();
        $carregCanaDAO = new CarregCanaDAO();

        $jsonObj = json_decode($info['dado']);
        $dados = $jsonObj->dados;

        foreach ($dados as $d) {
            $nroEquip = $d->nroEquip;
            $token = $d->token;
        }
        
        $v = $atualAplicDAO->verToken($token);
        
        if ($v > 0) {

            $dadosLocal = array("dados" => $carregCanaDAO->retLocalCarreg($nroEquip));
            $resLocal = json_encode($dadosLocal);
            
            return $resLocal;
        
        }

    }
    
}
