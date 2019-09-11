<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/dao/LogDAO.class.php');
require_once('../model/dao/BoletimMMDAO.class.php');
require_once('../model/dao/ApontMMDAO.class.php');
require_once('../model/dao/ImplementoMMDAO.class.php');
require_once('../model/dao/RendimentoMMDAO.class.php');

/**
 * Description of InserirDadosMM
 *
 * @author anderson
 */
class MotoMecCTR {

    public function salvarBolAberto($versao, $info, $pagina) {

        $dados = $info['dado'];
        $pagina = $pagina . '-' . $versao;
        $this->salvarLog($dados, $pagina);

        $versao = str_replace("_", ".", $versao);

        if ($versao >= 2.00) {

            $pos1 = strpos($dados, "_") + 1;
            $pos2 = strpos($dados, "|") + 1;

            $bolmm = substr($dados, 0, ($pos1 - 1));
            $apontmm = substr($dados, $pos1, (($pos2 - 1) - $pos1));
            $impl = substr($dados, $pos2);

            $jsonObjBoletim = json_decode($bolmm);
            $jsonObjAponta = json_decode($apontmm);
            $jsonObjImplemento = json_decode($impl);

            $dadosBoletim = $jsonObjBoletim->boletim;
            $dadosAponta = $jsonObjAponta->aponta;
            $dadosImplemento = $jsonObjImplemento->implemento;

            $ret = $this->salvarBoletoAberto($dadosBoletim, $dadosAponta, $dadosImplemento);

            return "BOLABERTOMM_" . $ret;
        }
    }

    public function salvarApont($versao, $info, $pagina) {

        $dados = $info['dado'];
        $pagina = $pagina . '-' . $versao;
        $this->salvarLog($dados, $pagina);

        $versao = str_replace("_", ".", $versao);

        if ($versao >= 2.00) {

            $pos1 = strpos($dados, "_") + 1;

            $apontmm = substr($dados, 0, ($pos1 - 1));
            $imp = substr($dados, $pos1);

            $jsonObjAponta = json_decode($apontmm);
            $jsonObjImplemento = json_decode($imp);

            $dadosAponta = $jsonObjAponta->aponta;
            $dadosImplemento = $jsonObjImplemento->implemento;

            $ret = $this->salvarApontExt($dadosAponta, $dadosImplemento);

            return 'APONTMM_' . $ret;
        }
    }

    public function salvarBolFechado($versao, $info, $pagina) {

        $dados = $info['dado'];
        $pagina = $pagina . '-' . $versao;
        $this->salvarLog($dados, $pagina);

        $versao = str_replace("_", ".", $versao);

        if ($versao >= 2.00) {

            $pos1 = strpos($dados, "_") + 1;
            $pos2 = strpos($dados, "|") + 1;
            $pos3 = strpos($dados, "#") + 1;

            $bolmm = substr($dados, 0, ($pos1 - 1));
            $apontmm = substr($dados, $pos1, (($pos2 - 1) - $pos1));
            $impl = substr($dados, $pos2, (($pos3 - 1) - $pos2));
            $rend = substr($dados, $pos3);

            $jsonObjBoletim = json_decode($bolmm);
            $jsonObjAponta = json_decode($apontmm);
            $jsonObjImplemento = json_decode($impl);
            $jsonObjRendimento = json_decode($rend);

            $dadosBoletim = $jsonObjBoletim->boletim;
            $dadosAponta = $jsonObjAponta->aponta;
            $dadosImplemento = $jsonObjImplemento->implemento;
            $dadosRendimento = $jsonObjRendimento->rendimento;

            $ret = $this->salvarBoletoFechado($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosRendimento);

            return 'BOLFECHADOMM_' . $ret;
        }
    }

    private function salvarLog($dados, $pagina) {
        $logDAO = new LogDAO();
        $logDAO->salvarDados($dados, $pagina);
    }

    private function salvarBoletoAberto($dadosBoletim, $dadosAponta, $dadosImplemento) {
        $boletimMMDAO = new BoletimMMDAO();
        $idBolMMArray = array();
        foreach ($dadosBoletim as $bol) {
            $v = $boletimMMDAO->verifBoletimMM($bol);
            if ($v == 0) {
                $boletimMMDAO->insBoletimMMAberto($bol);
            }
            $idBolBD = $boletimMMDAO->idBoletimMM($bol);
            $retApont = $this->salvarApontBol($idBolBD, $bol->idBolMM, $dadosAponta, $dadosImplemento);
            $idBolMMArray[] = array("idBolMM" => $bol->idBolMM, "idExtBolMM" => $idBolBD);
        }
        $dadoBol = array("boletim"=>$idBolMMArray);
        $retBol = json_encode($dadoBol);
        return $retBol . "|" . $retApont;
    }

    private function salvarBoletoFechado($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosRendimento) {
        $boletimMMDAO = new BoletimMMDAO();
        $idBolMMArray = array();
        foreach ($dadosBoletim as $bol) {
            $v = $boletimMMDAO->verifBoletimMM($bol);
            if ($v == 0) {
                $boletimMMDAO->insBoletimMMFechado($bol);
                $idBolBD = $boletimMMDAO->idBoletimMM($bol);
            } else {
                $idBolBD = $boletimMMDAO->idBoletimMM($bol);
                $boletimMMDAO->updateBoletimMMFechado($idBolBD, $bol);
            }
            $this->salvarApontBol($idBolBD, $bol->idBolMM, $dadosAponta, $dadosImplemento);
            $this->salvarRendimento($idBolBD, $bol->idBolMM, $dadosRendimento);
            $apontMMDAO = new ApontMMDAO();
            $qtdeApontBolMM = $apontMMDAO->verifQtdeApontMM($idBolBD);
            $idBolMMArray[] = array("idBolMM" => $bol->idBolMM, "qtdeApontBolMM" => $qtdeApontBolMM);
        }
        $dadoBol = array("boletim"=>$idBolMMArray);
        $retBol = json_encode($dadoBol);
        return $retBol;
    }

    private function salvarApontExt($dadosAponta, $dadosImplemento) {
        $apontMMDAO = new ApontMMDAO();
        $idApontArray = array();
        foreach ($dadosAponta as $apont) {
            $v = $apontMMDAO->verifApontMM($apont->idExtBolApontMM, $apont);
            if ($v == 0) {
                $apontMMDAO->insApontMM($apont->idExtBolApontMM, $apont);
            }
            $idApont = $apontMMDAO->idApontMM($apont->idExtBolApontMM, $apont);
            $this->salvarImplemento($idApont, $apont->idApontMM, $dadosImplemento);
            $idApontArray[] = array("idApontMM" => $apont->idApontMM);
        }
        $dadoApont = array("apont"=>$idApontArray);
        $retApont = json_encode($dadoApont);
        return $retApont;
    }

    private function salvarApontBol($idBolBD, $idBolCel, $dadosAponta, $dadosImplemento) {
        $apontMMDAO = new ApontMMDAO();
        $idApontArray = array();
        foreach ($dadosAponta as $apont) {
            if ($idBolCel == $apont->idBolApontMM) {
                $v = $apontMMDAO->verifApontMM($idBolBD, $apont);
                if ($v == 0) {
                    $apontMMDAO->insApontMM($idBolBD, $apont);
                }
                $idApont = $apontMMDAO->idApontMM($idBolBD, $apont);
                $this->salvarImplemento($idApont, $apont->idApontMM, $dadosImplemento);
                $idApontArray[] = array("idApontMM" => $apont->idApontMM);
            }
        }
        $dadoApont = array("apont"=>$idApontArray);
        $retApont = json_encode($dadoApont);
        return $retApont;
    }

    private function salvarImplemento($idApontaBD, $idApontaCel, $dadosImplemento) {
        $implementoMMDAO = new ImplementoMMDAO();
        foreach ($dadosImplemento as $imp) {
            if ($idApontaCel == $imp->idApontImpleMM) {
                if ($imp->codEquipImpleMM != 0) {
                    $v = $implementoMMDAO->verifImplementoMM($idApontaBD, $imp);
                    if ($v == 0) {
                        $implementoMMDAO->insImplementoMM($idApontaBD, $imp);
                    }
                }
            }
        }
    }

    private function salvarRendimento($idBolBD, $idBolCel, $dadosRendimento) {
        $rendimentoMMDAO = new RendimentoMMDAO();
        foreach ($dadosRendimento as $rend) {
            if ($idBolCel == $rend->idBolRendMM) {
                $v = $rendimentoMMDAO->verifRendimentoMM($idBolBD, $rend);
                if ($v == 0) {
                    $rendimentoMMDAO->insRendimentoMM($idBolBD, $rend);
                }
            }
        }
    }

}
