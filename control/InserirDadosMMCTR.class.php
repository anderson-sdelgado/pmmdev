<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('./model/dao/InserirLogDAO.class.php');
require_once('./model/dao/BoletimMMDAO.class.php');
require_once('./model/dao/ApontMMDAO.class.php');
require_once('./model/dao/ImplementoMMDAO.class.php');
require_once('./model/dao/RendimentoMMDAO.class.php');
require_once('./model/dao/BoletimPneuDAO.class.php');
require_once('./model/dao/ItemMedPneuDAO.class.php');

/**
 * Description of InserirDadosMM
 *
 * @author anderson
 */
class InserirDadosMMCTR {

    public function salvarDadosBolAbertoMM($info, $pagina) {

        $dados = $info['dado'];
        $this->salvarLog($dados, $pagina);

        if ($pagina == 'inserirbolabertomm2') {

            $pos1 = strpos($dados, "_") + 1;
            $pos2 = strpos($dados, "|") + 1;
            $pos3 = strpos($dados, "#") + 1;
            $pos4 = strpos($dados, "?") + 1;

            $bolmm = substr($dados, 0, ($pos1 - 1));
            $apontmm = substr($dados, $pos1, (($pos2 - 1) - $pos1));
            $impl = substr($dados, $pos2, (($pos3 - 1) - $pos2));
            $bolpneu = substr($dados, $pos3, (($pos4 - 1) - $pos3));
            $itempneu = substr($dados, $pos4);

            $jsonObjBoletim = json_decode($bolmm);
            $jsonObjAponta = json_decode($apontmm);
            $jsonObjImplemento = json_decode($impl);
            $jsonObjBolPneu = json_decode($bolpneu);
            $jsonObjItemPneu = json_decode($itempneu);

            $dadosBoletim = $jsonObjBoletim->boletim;
            $dadosAponta = $jsonObjAponta->aponta;
            $dadosImplemento = $jsonObjImplemento->implemento;
            $dadosBolPneu = $jsonObjBolPneu->bolpneu;
            $dadosItemPneu = $jsonObjItemPneu->itempneu;

            $idBol = $this->salvarBolAberto($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosBolPneu, $dadosItemPneu);
        } else {

            $pos1 = strpos($dados, "_") + 1;
            $pos2 = strpos($dados, "|") + 1;
            $pos3 = strpos($dados, "?") + 1;

            $b = substr($dados, 0, ($pos1 - 1));
            $amm = substr($dados, $pos1, (($pos2 - 1) - $pos1));
            $i = substr($dados, $pos2, (($pos3 - 1) - $pos2));

            $jsonObjBoletim = json_decode($b);
            $jsonObjAponta = json_decode($amm);
            $jsonObjImplemento = json_decode($i);

            $dadosBoletim = $jsonObjBoletim->boletim;
            $dadosAponta = $jsonObjAponta->aponta;
            $dadosImplemento = $jsonObjImplemento->implemento;

            if ($pagina == 'inserirbolabertodt') {
                $idBol = $this->salvarBolAbertoCDC($dadosBoletim, $dadosAponta, $dadosImplemento);
            } elseif ($pagina == 'insbolabertomm') {
                $idBol = $this->salvarBolAbertoSDC($dadosBoletim, $dadosAponta, $dadosImplemento);
            }
        }

        return "GRAVOU+id=" . $idBol . "_";
    }

    public function salvarDadosApontMM($info, $pagina) {

        $dados = $info['dado'];
        $this->salvarLog($dados, $pagina);

        if ($pagina == 'inserirapontmm2') {

            $pos1 = strpos($dados, "_") + 1;
            $pos2 = strpos($dados, "|") + 1;
            $pos3 = strpos($dados, "#") + 1;

            $apontmm = substr($dados, 0, ($pos1 - 1));
            $imp = substr($dados, $pos1, (($pos2 - 1) - $pos1));
            $bolpneu = substr($dados, $pos2, (($pos3 - 1) - $pos2));
            $itempneu = substr($dados, $pos3);

            $jsonObjAponta = json_decode($apontmm);
            $jsonObjImplemento = json_decode($imp);
            $jsonObjBolPneu = json_decode($bolpneu);
            $jsonObjItemPneu = json_decode($itempneu);

            $dadosAponta = $jsonObjAponta->aponta;
            $dadosImplemento = $jsonObjImplemento->implemento;
            $dadosBolPneu = $jsonObjBolPneu->bolpneu;
            $dadosItemPneu = $jsonObjItemPneu->itempneu;

            $idBol = $this->salvarApont($dadosAponta, $dadosImplemento, $dadosBolPneu, $dadosItemPneu);
        } else {

            $pos1 = strpos($dados, "|") + 1;
            $pos2 = strpos($dados, "?") + 1;
            $amm = substr($dados, 0, ($pos1 - 1));
            $i = substr($dados, $pos1, (($pos2 - 1) - $pos1));

            $jsonObjAponta = json_decode($amm);
            $jsonObjImplemento = json_decode($i);

            $dadosAponta = $jsonObjAponta->aponta;
            $dadosImplemento = $jsonObjImplemento->implemento;

            if ($pagina == 'inserirapontdt') {
                $idBol = $this->salvarApontCDC($dadosAponta, $dadosImplemento, $dadosBolPneu, $dadosItemPneu);
            } elseif ($pagina == 'insapontmm') {
                $idBol = $this->salvarApontSDC($dadosAponta, $dadosImplemento, $dadosBolPneu, $dadosItemPneu);
            }
        }

        return 'GRAVOU-APONTAMM';
    }

    public function salvarDadosBolFechadoMM($info, $pagina) {

        $dados = $info['dado'];
        $this->salvarLog($dados, $pagina);

        if ($pagina == 'inserirbolfechado2') {

            $pos1 = strpos($dados, "_") + 1;
            $pos2 = strpos($dados, "|") + 1;
            $pos3 = strpos($dados, "#") + 1;
            $pos4 = strpos($dados, "?") + 1;
            $pos5 = strpos($dados, "@") + 1;

            $bolmm = substr($dados, 0, ($pos1 - 1));
            $apontmm = substr($dados, $pos1, (($pos2 - 1) - $pos1));
            $impl = substr($dados, $pos2, (($pos3 - 1) - $pos2));
            $rend = substr($dados, $pos3, (($pos4 - 1) - $pos3));
            $bolpneu = substr($dados, $pos4, (($pos5 - 1) - $pos4));
            $itempneu = substr($dados, $pos5);

            $jsonObjBoletim = json_decode($bolmm);
            $jsonObjAponta = json_decode($apontmm);
            $jsonObjImplemento = json_decode($impl);
            $jsonObjRendimento = json_decode($rend);
            $jsonObjBolPneu = json_decode($bolpneu);
            $jsonObjItemPneu = json_decode($itempneu);

            $dadosBoletim = $jsonObjBoletim->boletim;
            $dadosAponta = $jsonObjAponta->aponta;
            $dadosImplemento = $jsonObjImplemento->implemento;
            $dadosRendimento = $jsonObjRendimento->rendimento;
            $dadosBolPneu = $jsonObjBolPneu->bolpneu;
            $dadosItemPneu = $jsonObjItemPneu->itempneu;

            $this->salvarBolFechado($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosRendimento, $dadosBolPneu, $dadosItemPneu);
        } else {

            $pos1 = strpos($dados, "_") + 1;
            $pos2 = strpos($dados, "|") + 1;
            $pos3 = strpos($dados, "#") + 1;
            $pos4 = strpos($dados, "?") + 1;
            $pos5 = strpos($dados, "@") + 1;
            
            $c = substr($dados, 0, ($pos1 - 1));
            $amm = substr($dados, $pos1, (($pos2 - 1) - $pos1));
            $i = substr($dados, $pos2, (($pos3 - 1) - $pos2));
            $r = substr($dados, $pos3, (($pos4 - 1) - $pos3));

            $jsonObjBoletim = json_decode($c);
            $jsonObjAponta = json_decode($amm);
            $jsonObjImplemento = json_decode($i);
            $jsonObjRendimento = json_decode($r);
            
            $dadosBoletim = $jsonObjBoletim->boletim;
            $dadosAponta = $jsonObjAponta->aponta;
            $dadosImplemento = $jsonObjImplemento->implemento;
            $dadosRendimento = $jsonObjRendimento->rendimento;

            if ($pagina == 'inserirbolfechadodt') {
                $this->salvarBolFechadoCDC($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosRendimento);
            } elseif ($pagina == 'insbolfechadomm') {
                $this->salvarBolFechadoSDC($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosRendimento);
            }
        }

        return 'GRAVOU-BOLFECHADO';
    }

    private function salvarLog($dados, $pagina) {
        $inserirLogDAO = new InserirLogDAO();
        $inserirLogDAO->salvarDados($dados, $pagina);
    }

    ///////////////////////////////////////////////VERSAO 2/////////////////////////////////////////////////////////////
    //    DADOS QUE VEM DAS PAGINAS INSERIRBOLABERTOMM2, INSERIRBOLFECHADOMM2 E INSERIRAPONTAMM2

    private function salvarBolAberto($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosBolPneu, $dadosItemPneu) {
        $boletimMMDAO = new BoletimMMDAO();
        foreach ($dadosBoletim as $bol) {
            $v = $boletimMMDAO->verifBoletimMM($bol);
            if ($v == 0) {
                $boletimMMDAO->insBoletimMMAberto($bol);
            }
            $idBol = $boletimMMDAO->idBoletimMM($bol);
            $this->salvarApont($idBol, $bol->idBoletim, $dadosAponta, $dadosImplemento, $dadosBolPneu, $dadosItemPneu);
        }
        return $idBol;
    }

    private function salvarBolFechado($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosRendimento, $dadosBolPneu, $dadosItemPneu) {
        $boletimMMDAO = new BoletimMMDAO();
        foreach ($dadosBoletim as $bol) {
            $v = $boletimMMDAO->verifBoletimMM($bol);
            if ($v == 0) {
                $boletimMMDAO->insBoletimMMFechado($bol);
                $idBol = $boletimMMDAO->idBoletimMM($bol);
            } else {
                $idBol = $boletimMMDAO->idBoletimMM($bol);
                $boletimMMDAO->altBoletimMMFechado($idBol, $bol);
            }
            $this->salvarApontBol($idBol, $bol->idBoletim, $dadosAponta, $dadosImplemento, $dadosBolPneu, $dadosItemPneu);
            $this->salvarRendimento($idBol, $bol->idBoletim, $dadosRendimento);
        }
    }

    private function salvarApont($dadosAponta, $dadosImplemento, $dadosBolPneu, $dadosItemPneu) {
        $apontMMDAO = new ApontMMDAO();
        foreach ($dadosAponta as $apont) {
            $v = $apontMMDAO->verifApontMM($apont->idExtBolAponta, $apont);
            if ($v == 0) {
                $apontMMDAO->insApontMM($apont->idExtBolAponta, $apont);
            }
            $this->salvarImplemento($apont->idExtBolAponta, $apont->idAponta, $dadosImplemento);
            $this->salvarBoletimPneu($apont->idExtBolAponta, $apont->idAponta, $dadosBolPneu, $dadosItemPneu);
        }
    }

    private function salvarApontBol($idBolBD, $idBolCel, $dadosAponta, $dadosImplemento, $dadosBolPneu, $dadosItemPneu) {
        $apontMMDAO = new ApontMMDAO();
        foreach ($dadosAponta as $apont) {
            if ($idBolCel == $apont->idBolAponta) {
                $v = $apontMMDAO->verifApontMM($idBolBD, $apont);
                if ($v == 0) {
                    $apontMMDAO->insApontMM($idBolBD, $apont);
                }
                $idApont = $apontMMDAO->idApontMM($idBolBD, $apont);
                $this->salvarImplemento($idApont, $apont->idAponta, $dadosImplemento);
                $this->salvarBoletimPneu($idApont, $apont->idAponta, $dadosBolPneu, $dadosItemPneu);
            }
        }
    }

    private function salvarImplemento($idApontaBD, $idApontaCel, $dadosImplemento) {
        $implementoMMDAO = new ImplementoMMDAO();
        foreach ($dadosImplemento as $imp) {
            if ($idApontaCel == $imp->idApontImplemento) {
                if ($imp->codEquipImplemento != 0) {
                    $v = $implementoMMDAO->verifImplementoMM($idApontaBD, $imp);
                    if ($v == 0) {
                        $implementoMMDAO->insImplementoMM($idApontaBD, $imp);
                    }
                }
            }
        }
    }

    private function salvarBoletimPneu($idApontBD, $idApontCel, $dadosBolPneu, $dadosItemPneu) {
        $boletimPneuDAO = new BoletimPneuDAO();
        foreach ($dadosBolPneu as $bolPneu) {
            if ($idApontCel == $bolPneu->idApontBolPneu) {
                $v = $boletimPneuDAO->verifBoletimPneu($idApontBD, $bolPneu, 1);
                if ($v == 0) {
                    $boletimPneuDAO->insBoletimPneu($idApontBD, $bolPneu, 1);
                }
                $idBolPneu = $boletimPneuDAO->idBoletimPneu($idApontBD, $bolPneu, 1);
                $this->salvarItemMedPneu($idBolPneu, $bolPneu->idBolPneu, $dadosItemPneu);
            }
        }
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

    private function salvarRendimento($idBolBD, $idBolCel, $dadosRendimento) {
        $rendimentoMMDAO = new RendimentoMMDAO();
        foreach ($dadosRendimento as $rend) {
            if ($idBolCel == $rend->idBolRendimento) {
                $v = $rendimentoMMDAO->verifRendimentoMM($idBolBD, $rend);
                if ($v == 0) {
                    $rendimentoMMDAO->insRendimentoMM($idBolBD, $rend);
                }
            }
        }
    }

    ///////////////////////////////////////////////VERSAO 1 COM DATA DE CELULAR//////////////////////////////////////////////////////////
    //    DADOS QUE VEM DAS PAGINAS INSERIRBOLABERTODT, INSERIRBOLFECHADODT E INSERIRAPONTDT

    private function salvarBolAbertoCDC($dadosBoletim, $dadosAponta, $dadosImplemento) {
        $boletimMMDAO = new BoletimMMDAO();
        foreach ($dadosBoletim as $bol) {
            $v = $boletimMMDAO->verifBoletimMMCDC($bol);
            if ($v == 0) {
                $boletimMMDAO->insBoletimMMAbertoCDC($bol);
            }
            $idBol = $boletimMMDAO->idBoletimMMCDC($bol);
            $this->salvarApontCDC($idBol, $bol->idBoletim, $dadosAponta, $dadosImplemento);
        }
        return $idBol;
    }

    private function salvarBolFechadoCDC($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosRendimento) {
        $boletimMMDAO = new BoletimMMDAO();
        foreach ($dadosBoletim as $bol) {
            $v = $boletimMMDAO->verifBoletimMMCDC($bol);
            if ($v == 0) {
                $boletimMMDAO->insBoletimMMFechadoCDC($bol);
                $idBol = $boletimMMDAO->idBoletimMMCDC($bol);
            } else {
                $idBol = $boletimMMDAO->idBoletimMMCDC($bol);
                $boletimMMDAO->altBoletimMMFechadoCDC($idBol, $bol);
            }
            $this->salvarApontBolCDC($idBol, $bol->idBoletim, $dadosAponta, $dadosImplemento);
            $this->salvarRendimentoCDC($idBol, $bol->idBoletim, $dadosRendimento);
        }
    }

    private function salvarApontCDC($dadosAponta, $dadosImplemento) {
        $apontMMDAO = new ApontMMDAO();
        foreach ($dadosAponta as $apont) {
            $v = $apontMMDAO->verifApontMMCDC($apont->idExtBolAponta, $apont);
            if ($v == 0) {
                $apontMMDAO->insApontMMCDC($apont->idExtBolAponta, $apont);
            }
            $this->salvarImplementoCDC($apont->idExtBolAponta, $apont->idAponta, $dadosImplemento);
        }
    }

    private function salvarApontBolCDC($idBolBD, $idBolCel, $dadosAponta, $dadosImplemento) {
        $apontMMDAO = new ApontMMDAO();
        foreach ($dadosAponta as $apont) {
            if ($idBolCel == $apont->idBolAponta) {
                $v = $apontMMDAO->verifApontMMCDC($idBolBD, $apont);
                if ($v == 0) {
                    $apontMMDAO->insApontMMCDC($idBolBD, $apont);
                }
                $idApont = $apontMMDAO->idApontMMCDC($idBolBD, $apont);
                $this->salvarImplementoCDC($idApont, $apont->idAponta, $dadosImplemento);
            }
        }
    }

    private function salvarImplementoCDC($idApontaBD, $idApontaCel, $dadosImplemento) {
        $implementoMMDAO = new ImplementoMMDAO();
        foreach ($dadosImplemento as $imp) {
            if ($idApontaCel == $imp->idApontImplemento) {
                if ($imp->codEquipImplemento != 0) {
                    $v = $implementoMMDAO->verifImplementoMMCDC($idApontaBD, $imp);
                    if ($v == 0) {
                        $implementoMMDAO->insImplementoMMCDC($idApontaBD, $imp);
                    }
                }
            }
        }
    }

    private function salvarRendimentoCDC($idBolBD, $idBolCel, $dadosRendimento) {
        $rendimentoMMDAO = new RendimentoMMDAO();
        foreach ($dadosRendimento as $rend) {
            if ($idBolCel == $rend->idBolRendimento) {
                $v = $rendimentoMMDAO->verifRendimentoMMCDC($idBolBD, $rend);
                if ($v == 0) {
                    $rendimentoMMDAO->insRendimentoMMCDC($idBolBD, $rend);
                }
            }
        }
    }

    ///////////////////////////////////////////////SEM DATA DE CELULAR//////////////////////////////////////////////////////////
    //    DADOS QUE VEM DAS PAGINAS INSBOLABERTOMM, INSBOLFECHADOMM E INSAPONTMM

    private function salvarBolAbertoSDC($dadosBoletim, $dadosAponta, $dadosImplemento) {
        $boletimMMDAO = new BoletimMMDAO();
        foreach ($dadosBoletim as $bol) {
            $v = $boletimMMDAO->verifBoletimMMSDC($bol);
            if ($v == 0) {
                $boletimMMDAO->insBoletimMMAbertoSDC($bol);
            }
            $idBol = $boletimMMDAO->idBoletimMMSDC($bol);
            $this->salvarApontSDC($idBol, $bol->idBoletim, $dadosAponta, $dadosImplemento);
        }
        return $idBol;
    }

    private function salvarBolFechadoSDC($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosRendimento) {
        $boletimMMDAO = new BoletimMMDAO();
        foreach ($dadosBoletim as $bol) {
            $v = $boletimMMDAO->verifBoletimMMSDC($bol);
            if ($v == 0) {
                $boletimMMDAO->insBoletimMMFechadoSDC($bol);
                $idBol = $boletimMMDAO->idBoletimMMSDC($bol);
            } else {
                $idBol = $boletimMMDAO->idBoletimMMSDC($bol);
                $boletimMMDAO->altBoletimMMFechadoSDC($idBol, $bol);
            }
            $this->salvarApontBolSDC($idBol, $bol->idBoletim, $dadosAponta, $dadosImplemento);
            $this->salvarRendimentoSDC($idBol, $bol->idBoletim, $dadosRendimento, $bol->dthrFimBoletim);
        }
    }

    private function salvarApontSDC($dadosAponta, $dadosImplemento) {
        $apontMMDAO = new ApontMMDAO();
        foreach ($dadosAponta as $apont) {
            $v = $apontMMDAO->verifApontMMSDC($apont->idExtBolAponta, $apont);
            if ($v == 0) {
                $apontMMDAO->insApontMMSDC($apont->idExtBolAponta, $apont);
            }
            $this->salvarImplementoSDC($apont->idExtBolAponta, $apont->idAponta, $dadosImplemento);
        }
    }

    private function salvarApontBolSDC($idBolBD, $idBolCel, $dadosAponta, $dadosImplemento) {
        $apontMMDAO = new ApontMMDAO();
        foreach ($dadosAponta as $apont) {
            if ($idBolCel == $apont->idBolAponta) {
                $v = $apontMMDAO->verifApontMMSDC($idBolBD, $apont);
                if ($v == 0) {
                    $apontMMDAO->insApontMMSDC($idBolBD, $apont);
                }
                $idApont = $apontMMDAO->idApontMMSDC($idBolBD, $apont);
                $this->salvarImplementoSDC($idApont, $apont->idAponta, $dadosImplemento);
            }
        }
    }

    private function salvarImplementoSDC($idApontaBD, $idApontaCel, $dadosImplemento) {
        $implementoMMDAO = new ImplementoMMDAO();
        foreach ($dadosImplemento as $imp) {
            if ($idApontaCel == $imp->idApontImplemento) {
                if ($imp->codEquipImplemento != 0) {
                    $v = $implementoMMDAO->verifImplementoMMSDC($idApontaBD, $imp);
                    if ($v == 0) {
                        $implementoMMDAO->insImplementoMMSDC($idApontaBD, $imp);
                    }
                }
            }
        }
    }

    private function salvarRendimentoSDC($idBolBD, $idBolCel, $dadosRendimento, $dthrBoletim) {
        $rendimentoMMDAO = new RendimentoMMDAO();
        foreach ($dadosRendimento as $rend) {
            if ($idBolCel == $rend->idBolRendimento) {
                $v = $rendimentoMMDAO->verifRendimentoMMSDC($idBolBD, $rend, $dthrBoletim);
                if ($v == 0) {
                    $rendimentoMMDAO->insRendimentoMMSDC($idBolBD, $rend, $dthrBoletim);
                }
            }
        }
    }

}
