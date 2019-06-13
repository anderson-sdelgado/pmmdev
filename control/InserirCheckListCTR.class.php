<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require('./model/dao/CabecCheckListDAO.class.php');
require('./model/dao/RespCheckListDAO.class.php');
require('./model/dao/InserirLogDAO.class.php');

/**
 * Description of InserirDadosCheckListCTR
 *
 * @author anderson
 */
class InserirCheckListCTR {

    //put your code here

    public function salvarDados($info, $pagina) {

        $inserirLogDAO = new InserirLogDAO();
        
        $dados = $info['dado'];
        $inserirLogDAO->salvarDados($dados, $pagina);

        $posicao = strpos($dados, "_") + 1;
        $cabec = substr($dados, 0, ($posicao - 1));
        $item = substr($dados, $posicao);

        $jsonObjCabec = json_decode($cabec);
        $jsonObjItem = json_decode($item);

        $dadosCab = $jsonObjCabec->cabecalho;
        $dadosItem = $jsonObjItem->item;

        if($pagina == 'inserirchecklist2'){
            $this->salvarBoletim($dadosCab, $dadosItem);
        }
        elseif($pagina == 'apontchecklistdt'){
            $this->salvarBoletimCDC($dadosCab, $dadosItem);
        }
        elseif($pagina == 'apontchecklist'){
            $this->salvarBoletimSDC($dadosCab, $dadosItem);
        }
        
        return 'GRAVOU-CHECKLIST';
    }

    private function salvarBoletim($dadosCab, $dadosItem) {
        $cabecCheckListDAO = new CabecCheckListDAO();
        foreach ($dadosCab as $d) {
            $v = $cabecCheckListDAO->verifCabecCheckList($d);
            if ($v == 0) {
                $cabecCheckListDAO->insCabecCheckList($d);
            }
            $idCabec = $cabecCheckListDAO->idCabecCheckList($d);
            $this->salvarApont($idCabec, $d->idCab, $dadosItem);
        }
    }
    
    private function salvarApont($idBolBD, $idBolCel, $dadosItem) {
        $respCheckListDAO = new RespCheckListDAO();
        foreach ($dadosItem as $i) {
            if ($idBolCel == $i->idCabIt) {
                $v = $respCheckListDAO->verifRespCheckList($idBolBD, $i);
                if ($v == 0) {
                    $respCheckListDAO->insRespCheckList($idBolBD, $i);
                }
            }
        }
    }
    
    private function salvarBoletimCDC($dadosCab, $dadosItem) {
        $cabecCheckListDAO = new CabecCheckListDAO();
        foreach ($dadosCab as $d) {
            $v = $cabecCheckListDAO->verifCabecCheckListCDC($d);
            if ($v == 0) {
                $cabecCheckListDAO->insCabecCheckListCDC($d);
            }
            $idCabec = $cabecCheckListDAO->idCabecCheckListCDC($d);
            $this->salvarApontCDC($idCabec, $d->idCab, $dadosItem);
        }
    }
    
    private function salvarApontCDC($idBolBD, $idBolCel, $dadosItem) {
        $respCheckListDAO = new RespCheckListDAO();
        foreach ($dadosItem as $i) {
            if ($idBolCel == $i->idCabIt) {
                $v = $respCheckListDAO->verifRespCheckListCDC($idBolBD, $i);
                if ($v == 0) {
                    $respCheckListDAO->insRespCheckListCDC($idBolBD, $i);
                }
            }
        }
    }

    private function salvarBoletimSDC($dadosCab, $dadosItem) {
        $cabecCheckListDAO = new CabecCheckListDAO();
        foreach ($dadosCab as $d) {
            $v = $cabecCheckListDAO->verifCabecCheckListSDC($d);
            if ($v == 0) {
                $cabecCheckListDAO->insCabecCheckListSDC($d);
            }
            $idCabec = $cabecCheckListDAO->idCabecCheckListSDC($d);
            $this->salvarApontSDC($idCabec, $d->idCab, $dadosItem);
        }
    }
    
    private function salvarApontSDC($idBolBD, $idBolCel, $dadosItem) {
        $respCheckListDAO = new RespCheckListDAO();
        foreach ($dadosItem as $i) {
            if ($idBolCel == $i->idCabIt) {
                $v = $respCheckListDAO->verifRespCheckListSDC($idBolBD, $i);
                if ($v == 0) {
                    $respCheckListDAO->insRespCheckListSDC($idBolBD, $i);
                }
            }
        }
    }
    
}
