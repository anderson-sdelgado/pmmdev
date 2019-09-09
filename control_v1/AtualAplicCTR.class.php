<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('./model_v1/dao/AtualAplicDAO.class.php');
/**
 * Description of AtualAplicativoCTR
 *
 * @author anderson
 */
class AtualAplicCTR {
    //put your code here
    
    public function verAtualAplic($info) {

        $atualAplicDAO = new AtualAplicDAO();
        
        $jsonObj = json_decode($info['dado']);
        $dados = $jsonObj->dados;
        
        foreach ($dados as $d) {
            $equip = $d->idEquipAtualizacao;
            $va = $d->versaoAtual;
            $cl = $d->idCheckList;
            $cla = $d->idCheckList;
        }
        $retorno = 'N_NAC';
        $v = $atualAplicDAO->verAtual($equip);
        if ($v == 0) {
            $atualAplicDAO->insAtual($equip, $va);
        } else {
            $result = $atualAplicDAO->retAtual($equip);
            foreach ($result as $item) {
                $vn = $item['VERSAO_NOVA'];
                $vab = $item['VERSAO_ATUAL'];
            }
            if ($va != $vab) {
                $atualAplicDAO->updAtualNova($equip, $va);
            } else {
                if ($va != $vn) {
                    $retorno = 'S';
                } else {
                    $result = $atualAplicDAO->verAtualCheckList($equip);
                    $vab = '';
                    foreach ($result as $item) {
                        $vab = $item['VERSAO_ATUAL'];
                        $vcl = $item['VERIF_CHECKLIST'];
                    }
                    if (strcmp($va, $vab) <> 0) {
                        $atualAplicDAO->updAtual($equip, $va);
                    } else {
                        if ($vcl == 1) {
                            $retorno = 'N_AC';
                        }
                    }
                    $cla = $atualAplicDAO->idCheckList($equip);
                    if ($cl != $cla) {
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
    
    public function verAtualAplicVersao1($info) {

        $atualAplicDAO = new AtualAplicDAO();

        $jsonObj = json_decode($info['dado']);
        $dados = $jsonObj->dados;
        
        foreach ($dados as $d) {
            $equip = $d->idEquipAtualizacao;
            $va = $d->versaoAtual;
        }
        $retorno = 'N_NAC';
        $v = $atualAplicDAO->verAtual($equip);
        if ($v == 0) {
            $atualAplicDAO->insAtual($equip, $va);
        } else {
            $result = $atualAplicDAO->retAtual($equip);
            foreach ($result as $item) {
                $vn = $item['VERSAO_NOVA'];
                $vab = $item['VERSAO_ATUAL'];
            }
            if ($va != $vab) {
                $atualAplicDAO->updAtualNova($equip, $va);
            } else {
                if ($va != $vn) {
                    $retorno = 'S';
                } else {
                    $result = $atualAplicDAO->verAtualCheckList($equip);
                    $vab = '';
                    foreach ($result as $item) {
                        $vab = $item['VERSAO_ATUAL'];
                        $vcl = $item['VERIF_CHECKLIST'];
                    }
                    if (strcmp($va, $vab) <> 0) {
                        $atualAplicDAO->updAtual($equip, $va);
                    } else {
                        if ($vcl == 1) {
                            $retorno = 'N_AC';
                        }
                    }
                }
            }
        }
        return $retorno;
    }
    
}
