<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/AtualAplicDAO.class.php');
/**
 * Description of AtualAplicativoCTR
 *
 * @author anderson
 */
class AtualAplicCTR {
    //put your code here

    private $base = 2;
    
    public function atualAplic($versao, $info) {

        $versao = str_replace("_", ".", $versao);
        $retorno = '';
        
        if(($versao >= 2.00) && ($versao < 3.00)){
        
            $atualAplicDAO = new AtualAplicDAO();

            $jsonObj = json_decode($info['dado']);
            $dados = $jsonObj->dados;

            foreach ($dados as $d) {
                $equip = $d->idEquipAtualizacao;
                $versaoAtual = $d->versaoAtual;
                $checkList = $d->idCheckList;
                $checkListBD = $d->idCheckList;
            }
            $retorno = 'N_NAC';
            $v = $atualAplicDAO->verAtual($equip, $this->base);
            if ($v == 0) {
                $atualAplicDAO->insAtual($equip, $versaoAtual, $this->base);
            } else {
                $result = $atualAplicDAO->retAtual($equip, $this->base);
                foreach ($result as $item) {
                    $versaoNova = $item['VERSAO_NOVA'];
                    $versaoAtualBD = $item['VERSAO_ATUAL'];
                }
                if ($versaoAtual != $versaoAtualBD) {
                    $atualAplicDAO->updAtualNova($equip, $versaoAtual, $this->base);
                } else {
                    if ($versaoAtual != $versaoNova) {
                        $retorno = 'S';
                    } else {
                        $result = $atualAplicDAO->verAtualCheckList($equip, $this->base);
                        $versaoAtualBD = '';
                        foreach ($result as $item) {
                            $versaoAtualBD = $item['VERSAO_ATUAL'];
                            $verCheckList = $item['VERIF_CHECKLIST'];
                        }
                        if (strcmp($versaoAtual, $versaoAtualBD) <> 0) {
                            $atualAplicDAO->updAtual($equip, $versaoAtual, $this->base);
                        } else {
                            if ($verCheckList == 1) {
                                $retorno = 'N_AC';
                            }
                        }
                        $checkListBD = $atualAplicDAO->idCheckList($equip, $this->base);
                        if ($checkList != $checkListBD) {
                            $retorno = 'N_AC';
                        }
                    }
                }
            }
            $dthr = $atualAplicDAO->dataHora($this->base);
            if (!$retorno == 'S') {
                $retorno = $retorno . "#" . $dthr;
            }
            
        }
        else if($versao >= 3.00){
        
            $atualAplicDAO = new AtualAplicDAO();

            $jsonObj = json_decode($info['dado']);
            $dados = $jsonObj->dados;

            foreach ($dados as $d) {
                $equip = $d->idEquipAtual;
                $versaoAtual = $d->versaoAtual;
                $checkList = $d->idCheckList;
                $checkListBD = $d->idCheckList;
            }
            
            $retAtualApp = 0;
            $retAtualCheckList = 0;
            $retFlagLogEnvio = 0;
            $retFlagLogErro = 0;
            
            $v = $atualAplicDAO->verAtual($equip, $this->base);
            if ($v == 0) {
                $atualAplicDAO->insAtual($equip, $versaoAtual, $this->base);
            } else {
                $result = $atualAplicDAO->retAtual($equip, $this->base);
                foreach ($result as $item) {
                    $versaoNova = $item['VERSAO_NOVA'];
                    $versaoAtualBD = $item['VERSAO_ATUAL'];
                    $retFlagLogEnvio = $item['FLAG_LOG_ENVIO'];
                    $retFlagLogErro = $item['FLAG_LOG_ERRO'];
                }
                if ($versaoAtual != $versaoAtualBD) {
                    $atualAplicDAO->updAtualNova($equip, $versaoAtual, $this->base);
                } else {
                    if ($versaoAtual != $versaoNova) {
                        $retAtualApp = 1;
                    } else {
                        $result = $atualAplicDAO->verAtualCheckList($equip, $this->base);
                        $versaoAtualBD = '';
                        foreach ($result as $item) {
                            $versaoAtualBD = $item['VERSAO_ATUAL'];
                            $verCheckList = $item['VERIF_CHECKLIST'];
                        }
                        if (strcmp($versaoAtual, $versaoAtualBD) <> 0) {
                            $atualAplicDAO->updAtual($equip, $versaoAtual, $this->base);
                        } else {
                            if ($verCheckList == 1) {
                                $retAtualCheckList = 1;
                            }
                        }
                        $checkListBD = $atualAplicDAO->idCheckList($equip, $this->base);
                        if ($checkList != $checkListBD) {
                            $retAtualCheckList = 1;
                        }
                    }
                }
            }
            $dthr = $atualAplicDAO->dataHora($this->base);
            
            $dado = array("flagAtualApp" => $retAtualApp, "flagAtualCheckList" => $retAtualCheckList
                , "flagLogEnvio" => $retFlagLogEnvio, "flagLogErro" => $retFlagLogErro
                , "dthr" => $dthr);

            $retorno = json_encode(array("dados" => array($dado)));
            
        }
        
        return $retorno;

    }
    
    public function atualAplicECM($versao, $info) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 2.00){
        
            $atualAplicDAO = new AtualAplicDAO();

            $jsonObj = json_decode($info['dado']);
            $dados = $jsonObj->dados;

            foreach ($dados as $d) {
                $equip = $d->idEquipAtualizacao;
                $versaoAtual = $d->versaoAtual;
                $checkList = $d->idCheckList;
                $checkListBD = $d->idCheckList;
            }
            $retorno = 'N_NAC';
            $v = $atualAplicDAO->verAtualECM($equip, $this->base);
            if ($v == 0) {
                $atualAplicDAO->insAtualECM($equip, $versaoAtual, $this->base);
            } else {
                $result = $atualAplicDAO->retAtualECM($equip, $this->base);
                foreach ($result as $item) {
                    $versaoNova = $item['VERSAO_NOVA'];
                    $versaoAtualBD = $item['VERSAO_ATUAL'];
                }
                if ($versaoAtual != $versaoAtualBD) {
                    $atualAplicDAO->updAtualNovaECM($equip, $versaoAtual, $this->base);
                } else {
                    if ($versaoAtual != $versaoNova) {
                        $retorno = 'S';
                    } else {
                        $result = $atualAplicDAO->verAtualCheckListECM($equip, $this->base);
                        $versaoAtualBD = '';
                        foreach ($result as $item) {
                            $versaoAtualBD = $item['VERSAO_ATUAL'];
                            $verCheckList = $item['VERIF_CHECKLIST'];
                        }
                        if (strcmp($versaoAtual, $versaoAtualBD) <> 0) {
                            $atualAplicDAO->updAtualECM($equip, $versaoAtual, $this->base);
                        } else {
                            if ($verCheckList == 1) {
                                $retorno = 'N_AC';
                            }
                        }
                        $checkListBD = $atualAplicDAO->idCheckListECM($equip, $this->base);
                        if ($checkList != $checkListBD) {
                            $retorno = 'N_AC';
                        }
                    }
                }
            }
            $dthr = $atualAplicDAO->dataHora($this->base);
            if ($retorno == 'S') {
                return $retorno;
            } else {
                return $retorno . "#" . $dthr;
            }
        
        }
        
    }
    
    public function atualAplicPCOMP($versao, $info) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 2.00){
        
            $atualAplicDAO = new AtualAplicDAO();

            $jsonObj = json_decode($info['dado']);
            $dados = $jsonObj->dados;

            foreach ($dados as $d) {
                $equip = $d->idEquipAtualizacao;
                $versaoAtual = $d->versaoAtual;
                $checkList = $d->idCheckList;
                $checkListBD = $d->idCheckList;
            }
            $retorno = 'N_NAC';
            $v = $atualAplicDAO->verAtualPCOMP($equip, $this->base);
            if ($v == 0) {
                $atualAplicDAO->insAtualPCOMP($equip, $versaoAtual, $this->base);
            } else {
                $result = $atualAplicDAO->retAtualPCOMP($equip, $this->base);
                foreach ($result as $item) {
                    $versaoNova = $item['VERSAO_NOVA'];
                    $versaoAtualBD = $item['VERSAO_ATUAL'];
                }
                if ($versaoAtual != $versaoAtualBD) {
                    $atualAplicDAO->updAtualNovaPCOMP($equip, $versaoAtual, $this->base);
                } else {
                    if ($versaoAtual != $versaoNova) {
                        $retorno = 'S';
                    } else {
                        $result = $atualAplicDAO->verAtualCheckListPCOMP($equip, $this->base);
                        $versaoAtualBD = '';
                        foreach ($result as $item) {
                            $versaoAtualBD = $item['VERSAO_ATUAL'];
                            $verCheckList = $item['VERIF_CHECKLIST'];
                        }
                        if (strcmp($versaoAtual, $versaoAtualBD) <> 0) {
                            $atualAplicDAO->updAtualPCOMP($equip, $versaoAtual, $this->base);
                        } else {
                            if ($verCheckList == 1) {
                                $retorno = 'N_AC';
                            }
                        }
                        $checkListBD = $atualAplicDAO->idCheckListPCOMP($equip, $this->base);
                        if ($checkList != $checkListBD) {
                            $retorno = 'N_AC';
                        }
                    }
                }
            }
            $dthr = $atualAplicDAO->dataHora($this->base);
            if ($retorno == 'S') {
                return $retorno;
            } else {
                return $retorno . "#" . $dthr;
            }
        
        }
        
    }
    
}
