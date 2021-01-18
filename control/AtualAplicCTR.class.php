<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/dao/AtualAplicDAO.class.php');
/**
 * Description of AtualAplicativoCTR
 *
 * @author anderson
 */
class AtualAplicCTR {
    //put your code here
    
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
            $v = $atualAplicDAO->verAtual($equip);
            if ($v == 0) {
                $atualAplicDAO->insAtual($equip, $versaoAtual);
            } else {
                $result = $atualAplicDAO->retAtual($equip);
                foreach ($result as $item) {
                    $versaoNova = $item['VERSAO_NOVA'];
                    $versaoAtualBD = $item['VERSAO_ATUAL'];
                }
                if ($versaoAtual != $versaoAtualBD) {
                    $atualAplicDAO->updAtualNova($equip, $versaoAtual);
                } else {
                    if ($versaoAtual != $versaoNova) {
                        $retorno = 'S';
                    } else {
                        $result = $atualAplicDAO->verAtualCheckList($equip);
                        $versaoAtualBD = '';
                        foreach ($result as $item) {
                            $versaoAtualBD = $item['VERSAO_ATUAL'];
                            $verCheckList = $item['VERIF_CHECKLIST'];
                        }
                        if (strcmp($versaoAtual, $versaoAtualBD) <> 0) {
                            $atualAplicDAO->updAtual($equip, $versaoAtual);
                        } else {
                            if ($verCheckList == 1) {
                                $retorno = 'N_AC';
                            }
                        }
                        $checkListBD = $atualAplicDAO->idCheckList($equip);
                        if ($checkList != $checkListBD) {
                            $retorno = 'N_AC';
                        }
                    }
                }
            }
            $dthr = $atualAplicDAO->dataHora();
            if (!$retorno == 'S') {
                $retorno = $retorno . "#" . $dthr;
            }
            
        }
        else if($versao >= 3.00){
        
            $atualAplicDAO = new AtualAplicDAO();

            $jsonObj = json_decode($info['dado']);
            $dados = $jsonObj->dados;

            foreach ($dados as $d) {
                $equip = $d->idEquipAtualizacao;
                $versaoAtual = $d->versaoAtual;
                $checkList = $d->idCheckList;
                $checkListBD = $d->idCheckList;
            }
            
            $retAtualApp = 0;
            $retAtualCheckList = 0;
            $retFlagLogEnvio = 0;
            $retFlagLogErro = 0;
            
            $v = $atualAplicDAO->verAtual($equip);
            if ($v == 0) {
                $atualAplicDAO->insAtual($equip, $versaoAtual);
            } else {
                $result = $atualAplicDAO->retAtual($equip);
                foreach ($result as $item) {
                    $versaoNova = $item['VERSAO_NOVA'];
                    $versaoAtualBD = $item['VERSAO_ATUAL'];
                    $retFlagLogEnvio = $item['FLAG_LOG_ENVIO'];
                    $retFlagLogErro = $item['FLAG_LOG_ERRO'];
                }
                if ($versaoAtual != $versaoAtualBD) {
                    $atualAplicDAO->updAtualNova($equip, $versaoAtual);
                } else {
                    if ($versaoAtual != $versaoNova) {
                        $retAtualApp = 1;
                    } else {
                        $result = $atualAplicDAO->verAtualCheckList($equip);
                        $versaoAtualBD = '';
                        foreach ($result as $item) {
                            $versaoAtualBD = $item['VERSAO_ATUAL'];
                            $verCheckList = $item['VERIF_CHECKLIST'];
                        }
                        if (strcmp($versaoAtual, $versaoAtualBD) <> 0) {
                            $atualAplicDAO->updAtual($equip, $versaoAtual);
                        } else {
                            if ($verCheckList == 1) {
                                $retAtualCheckList = 1;
                            }
                        }
                        $checkListBD = $atualAplicDAO->idCheckList($equip);
                        if ($checkList != $checkListBD) {
                            $retAtualCheckList = 1;
                        }
                    }
                }
            }
            $dthr = $atualAplicDAO->dataHora();
            
            $dado = array("flagAtualApp" => $retAtualApp, "flagAtualCheckList" => $retAtualCheckList
                , "flagLogEnvio" => $retFlagLogEnvio, "flagLogErro" => $retFlagLogErro);

            $retorno = array($dado);
            
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
            $v = $atualAplicDAO->verAtualECM($equip);
            if ($v == 0) {
                $atualAplicDAO->insAtualECM($equip, $versaoAtual);
            } else {
                $result = $atualAplicDAO->retAtualECM($equip);
                foreach ($result as $item) {
                    $versaoNova = $item['VERSAO_NOVA'];
                    $versaoAtualBD = $item['VERSAO_ATUAL'];
                }
                if ($versaoAtual != $versaoAtualBD) {
                    $atualAplicDAO->updAtualNovaECM($equip, $versaoAtual);
                } else {
                    if ($versaoAtual != $versaoNova) {
                        $retorno = 'S';
                    } else {
                        $result = $atualAplicDAO->verAtualCheckListECM($equip);
                        $versaoAtualBD = '';
                        foreach ($result as $item) {
                            $versaoAtualBD = $item['VERSAO_ATUAL'];
                            $verCheckList = $item['VERIF_CHECKLIST'];
                        }
                        if (strcmp($versaoAtual, $versaoAtualBD) <> 0) {
                            $atualAplicDAO->updAtualECM($equip, $versaoAtual);
                        } else {
                            if ($verCheckList == 1) {
                                $retorno = 'N_AC';
                            }
                        }
                        $checkListBD = $atualAplicDAO->idCheckListECM($equip);
                        if ($checkList != $checkListBD) {
                            $retorno = 'N_AC';
                        }
                    }
                }
            }
            $dthr = $atualAplicDAO->dataHora();
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
            $v = $atualAplicDAO->verAtualPCOMP($equip);
            if ($v == 0) {
                $atualAplicDAO->insAtualPCOMP($equip, $versaoAtual);
            } else {
                $result = $atualAplicDAO->retAtualPCOMP($equip);
                foreach ($result as $item) {
                    $versaoNova = $item['VERSAO_NOVA'];
                    $versaoAtualBD = $item['VERSAO_ATUAL'];
                }
                if ($versaoAtual != $versaoAtualBD) {
                    $atualAplicDAO->updAtualNovaPCOMP($equip, $versaoAtual);
                } else {
                    if ($versaoAtual != $versaoNova) {
                        $retorno = 'S';
                    } else {
                        $result = $atualAplicDAO->verAtualCheckListPCOMP($equip);
                        $versaoAtualBD = '';
                        foreach ($result as $item) {
                            $versaoAtualBD = $item['VERSAO_ATUAL'];
                            $verCheckList = $item['VERIF_CHECKLIST'];
                        }
                        if (strcmp($versaoAtual, $versaoAtualBD) <> 0) {
                            $atualAplicDAO->updAtualPCOMP($equip, $versaoAtual);
                        } else {
                            if ($verCheckList == 1) {
                                $retorno = 'N_AC';
                            }
                        }
                        $checkListBD = $atualAplicDAO->idCheckListPCOMP($equip);
                        if ($checkList != $checkListBD) {
                            $retorno = 'N_AC';
                        }
                    }
                }
            }
            $dthr = $atualAplicDAO->dataHora();
            if ($retorno == 'S') {
                return $retorno;
            } else {
                return $retorno . "#" . $dthr;
            }
        
        }
        
    }
    
}
