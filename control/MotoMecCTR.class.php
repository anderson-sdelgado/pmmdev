<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/dao/LogEnvioDAO.class.php');
require_once('../model/dao/BoletimMMDAO.class.php');
require_once('../model/dao/ApontMMDAO.class.php');
require_once('../model/dao/ImplementoMMDAO.class.php');
require_once('../model/dao/RendimentoMMDAO.class.php');
require_once('../model/dao/MovLeiraMMDAO.class.php');
require_once('../model/dao/CabecPneuDAO.class.php');
require_once('../model/dao/ItemPneuDAO.class.php');
require_once('../model/dao/MotoMecDAO.class.php');
/**
 * Description of InserirDadosMM
 *
 * @author anderson
 */
class MotoMecCTR {
    
    private $base = 2;

    public function salvarBolAberto($versao, $info, $pagina) {

        $dados = $info['dado'];
        $this->salvarLog($dados, $pagina, $versao);
        $versao = str_replace("_", ".", $versao);
        
        if ($versao >= 2.00) {
            
            $pos1 = strpos($dados, "_") + 1;
            $pos2 = strpos($dados, "|") + 1;
            $pos3 = strpos($dados, "#") + 1;
            $pos4 = strpos($dados, "?") + 1;
            $pos5 = strpos($dados, "@") + 1;

            $bolmm = substr($dados, 0, ($pos1 - 1));
            $apontmm = substr($dados, $pos1, (($pos2 - 1) - $pos1));
            $impl = substr($dados, $pos2, (($pos3 - 1) - $pos2));
            $movLeira = substr($dados, $pos3, (($pos4 - 1) - $pos3));
            $cabecPneu = substr($dados, $pos4, (($pos5 - 1) - $pos4));
            $itemPneu = substr($dados, $pos5);

            $jsonObjBoletim = json_decode($bolmm);
            $jsonObjAponta = json_decode($apontmm);
            $jsonObjImplemento = json_decode($impl);
            $jsonObjMovLeira = json_decode($movLeira);
            $jsonObjCabecPneu = json_decode($cabecPneu);
            $jsonObjItemPneu = json_decode($itemPneu);

            $dadosBoletim = $jsonObjBoletim->boletim;
            $dadosAponta = $jsonObjAponta->aponta;
            $dadosImplemento = $jsonObjImplemento->implemento;
            $dadosMovLeira = $jsonObjMovLeira->movleira;
            $dadosCabecPneu = $jsonObjCabecPneu->cabecpneu;
            $dadosItemPneu = $jsonObjItemPneu->itempneu;

            $ret = $this->salvarBoletoAberto($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosMovLeira, $dadosCabecPneu, $dadosItemPneu);

            return $ret;
        }
    }

    public function salvarBolFechado($versao, $info, $pagina) {

        $dados = $info['dado'];
        $this->salvarLog($dados, $pagina, $versao);
        $versao = str_replace("_", ".", $versao);

        if ($versao >= 2.00) {

            $pos1 = strpos($dados, "_") + 1;
            $pos2 = strpos($dados, "|") + 1;
            $pos3 = strpos($dados, "#") + 1;
            $pos4 = strpos($dados, "?") + 1;
            $pos5 = strpos($dados, "@") + 1;
            $pos6 = strpos($dados, "=") + 1;

            $bolmm = substr($dados, 0, ($pos1 - 1));
            $apontmm = substr($dados, $pos1, (($pos2 - 1) - $pos1));
            $impl = substr($dados, $pos2, (($pos3 - 1) - $pos2));
            $movLeira = substr($dados, $pos3, (($pos4 - 1) - $pos3));
            $cabecPneu = substr($dados, $pos4, (($pos5 - 1) - $pos4));
            $itemPneu = substr($dados, $pos5, (($pos6 - 1) - $pos5));
            $rend = substr($dados, $pos6);
            
            $jsonObjBoletim = json_decode($bolmm);
            $jsonObjAponta = json_decode($apontmm);
            $jsonObjImpl = json_decode($impl);
            $jsonObjRend = json_decode($rend);
            $jsonObjMovLeira = json_decode($movLeira);
            $jsonObjCabecPneu = json_decode($cabecPneu);
            $jsonObjItemPneu = json_decode($itemPneu);
            
            $dadosBoletim = $jsonObjBoletim->boletim;
            $dadosAponta = $jsonObjAponta->aponta;
            $dadosImplemento = $jsonObjImpl->implemento;
            $dadosRendimento = $jsonObjRend->rendimento;
            $dadosMovLeira = $jsonObjMovLeira->movleira;
            $dadosCabecPneu = $jsonObjCabecPneu->cabecpneu;
            $dadosItemPneu = $jsonObjItemPneu->itempneu;

            $ret = $this->salvarBoletoFech($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosMovLeira, $dadosRendimento, $dadosCabecPneu, $dadosItemPneu);

            return $ret;
        }
    }

    private function salvarLog($dados, $pagina, $versao) {
        $logEnvioDAO = new LogEnvioDAO();
        $logEnvioDAO->salvarDados($dados, $pagina, $versao, $this->base);
    }

    private function salvarBoletoAberto($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosMovLeira, $dadosCabecPneu, $dadosItemPneu) {
        
        $boletimMMDAO = new BoletimMMDAO();
        $idBolMMArray = array();
        
        foreach ($dadosBoletim as $bol) {
            $v = $boletimMMDAO->verifBoletimMM($bol, $this->base);
            if ($v == 0) {
                $boletimMMDAO->insBoletimMMAberto($bol, $this->base);
            }
            $idBolBD = $boletimMMDAO->idBoletimMM($bol, $this->base);
            $retApont = $this->salvarApontBol($idBolBD, $bol->idBolMM, $dadosAponta, $dadosImplemento, $dadosCabecPneu, $dadosItemPneu);
            $retMovLeira = $this->salvarMovLeira($idBolBD, $bol->idBolMM, $dadosMovLeira);
            $idBolMMArray[] = array("idBolMM" => $bol->idBolMM, "idExtBolMM" => $idBolBD);
        }
        
        $dadoBol = array("boletim"=>$idBolMMArray);
        $retBol = json_encode($dadoBol);
        return 'BOLABERTOMM_' . $retBol . "|" . $retApont . "#" . $retMovLeira;
        
    }

    private function salvarBoletoFech($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosMovLeira, $dadosRendimento, $dadosCabecPneu, $dadosItemPneu) {
        
        $boletimMMDAO = new BoletimMMDAO();
        $idBolMMArray = array();
        
        foreach ($dadosBoletim as $bol) {
            $v = $boletimMMDAO->verifBoletimMM($bol, $this->base);
            if ($v == 0) {
                $boletimMMDAO->insBoletimMMFechado($bol, $this->base);
                $idBolBD = $boletimMMDAO->idBoletimMM($bol, $this->base);
            } else {
                $idBolBD = $boletimMMDAO->idBoletimMM($bol, $this->base);
                $boletimMMDAO->updateBoletimMMFechado($idBolBD, $bol, $this->base);
            }
            $this->salvarApontBol($idBolBD, $bol->idBolMM, $dadosAponta, $dadosImplemento, $dadosCabecPneu, $dadosItemPneu);
            $this->salvarRendimento($idBolBD, $bol->idBolMM, $dadosRendimento);
            $this->salvarMovLeira($idBolBD, $bol->idBolMM, $dadosMovLeira);
            $apontMMDAO = new ApontMMDAO();
            $qtdeApontBolMM = $apontMMDAO->verifQtdeApontMM($idBolBD, $this->base);
            $idBolMMArray[] = array("idBolMM" => $bol->idBolMM, "qtdeApontBolMM" => $qtdeApontBolMM);
        }
        
        $dadoBol = array("boletim"=>$idBolMMArray);
        $retBol = json_encode($dadoBol);
        return 'BOLFECHADOMM_' . $retBol;
        
    }

    private function salvarApontBol($idBolBD, $idBolCel, $dadosAponta, $dadosImplemento, $dadosCabecPneu, $dadosItemPneu) {
        $apontMMDAO = new ApontMMDAO();
        $idApontArray = array();
        foreach ($dadosAponta as $apont) {
            if ($idBolCel == $apont->idBolApontMM) {
                $v = $apontMMDAO->verifApontMM($idBolBD, $apont, $this->base);
                if ($v == 0) {
                    $apontMMDAO->insApontMM($idBolBD, $apont, $this->base);
                }
                $idApontBD = $apontMMDAO->idApontMM($idBolBD, $apont, $this->base);
                $this->salvarImplemento($idApontBD, $apont->idApontMM, $dadosImplemento);
                $this->salvarCabecPneu($idApontBD, $apont->idApontMM, $dadosCabecPneu, $dadosItemPneu);
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
            if ($idApontaCel == $imp->idApontMM) {
                $v = $implementoMMDAO->verifImplementoMM($idApontaBD, $imp, $this->base);
                if ($v == 0) {
                    $implementoMMDAO->insImplementoMM($idApontaBD, $imp, $this->base);
                }
            }
        }
    }
    
    private function salvarCabecPneu($idApontaBD, $idApontaCel, $dadosCabecPneu, $dadosItemPneu) {
        $cabecPneuDAO = new CabecPneuDAO();
        foreach ($dadosCabecPneu as $cabecPneu) {
            if ($idApontaCel == $cabecPneu->idApontCabecPneu) {
                $v = $cabecPneuDAO->verifCabecPneu($idApontaBD, $cabecPneu, $this->base);
                if ($v == 0) {
                    $cabecPneuDAO->insCabecPneu($idApontaBD, $cabecPneu, 1, $this->base);
                }
                $idCabecPneuBD = $cabecPneuDAO->idCabecPneu($idApontaBD, $cabecPneu);
                $this->salvarItemPneu($idCabecPneuBD, $cabecPneu->idCabecPneu, $dadosItemPneu);
            }
        }
    }
    
    private function salvarItemPneu($idCabecPneuBD, $idCabecPneuCel, $dadosItemPneu) {
        $itemPneuDAO = new ItemPneuDAO();
        foreach ($dadosItemPneu as $itemPneu) {
            if ($idCabecPneuCel == $itemPneu->idCabecItemPneu) {
                $v = $itemPneuDAO->verifItemPneu($idCabecPneuBD, $itemPneu, $this->base);
                if ($v == 0) {
                    $itemPneuDAO->insItemPneu($idCabecPneuBD, $itemPneu, $this->base);
                }
            }
        }
    }

    private function salvarRendimento($idBolBD, $idBolCel, $dadosRendimento) {
        $rendimentoMMDAO = new RendimentoMMDAO();
        foreach ($dadosRendimento as $rend) {
            if ($idBolCel == $rend->idBolRendMM) {
                $v = $rendimentoMMDAO->verifRendimentoMM($idBolBD, $rend, $this->base);
                if ($v == 0) {
                    $rendimentoMMDAO->insRendimentoMM($idBolBD, $rend, $this->base);
                }
            }
        }
    }
    
    private function salvarMovLeira($idBolBD, $idBolCel, $dadosMovLeira) {
        $movLeiraMMDAO = new MovLeiraMMDAO();
        $idMovLeiraArray = array();
        foreach ($dadosMovLeira as $movLeira) {
            if ($idBolCel == $movLeira->idBolMovLeira) {
                $v = $movLeiraMMDAO->verifMovLeiraMM($idBolBD, $movLeira, $this->base);
                if ($v == 0) {
                    $movLeiraMMDAO->insMovLeiraMM($idBolBD, $movLeira, $this->base);
                }
                $idMovLeiraArray[] = array("idMovLeira" => $movLeira->idMovLeira);
            }
        }
        $dadoMovLeira = array("movLeira"=>$idMovLeiraArray);
        $retMovLeira = json_encode($dadoMovLeira);
        return $retMovLeira;
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
