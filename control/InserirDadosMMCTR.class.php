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

    //put your code here

    public function salvarDadosBolFechadoMM($info, $pagina) {

        $dados = $info['dado'];
        $this->salvarLog($dados, $pagina);

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

        if ($pagina == 'inserirbolfechado2') {
            $this->salvarBoletim($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosRendimento, $dadosBolPneu, $dadosItemPneu);
        } elseif ($pagina == 'inserirbolfechadodt') {
            $this->salvarBoletimCDC($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosRendimento, $dadosBolPneu, $dadosItemPneu);
        } elseif ($pagina == 'insbolfechadomm') {
            $this->salvarBoletimSDC($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosRendimento, $dadosBolPneu, $dadosItemPneu);
        }


        return 'GRAVOU-BOLFECHADO';
    }

    public function salvarDadosBolAbertoMM($info, $pagina) {

        $dados = $info['dado'];
        $this->salvarLog($dados, $pagina);

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

        $boletimMMDAO = new BoletimMMDAO();
        foreach ($dadosBoletim as $bol) {
            $v = $boletimMMDAO->verifBoletimMM($bol);
            if ($v == 0) {
                $boletimMMDAO->insBoletimMMAberto($bol);
            }
            $idBol = $boletimMMDAO->idBoletimMM($bol);
            $this->salvarApont($idBol, $bol->idBoletim, $dadosAponta, $dadosImplemento, $dadosBolPneu, $dadosItemPneu);
        }
        return "GRAVOU+id=" . $idBol . "_";
    }

    public function salvarDadosApontMM($info, $pagina) {

        $apontMMDAO = new ApontMMDAO();

        $dados = $info['dado'];
        $this->salvarLog($dados, $pagina);

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

        foreach ($dadosAponta as $apont) {
            $v = $apontMMDAO->verifApontMM($apont->idExtBolAponta, $apont);
            if ($v == 0) {
                $apontMMDAO->insApontMM($apont->idExtBolAponta, $apont);
            }
            $idApont = $apontMMDAO->idApontMM($apont->idExtBolAponta, $apont);
            $this->salvarImplemento($idApont, $apont->idAponta, $dadosImplemento);
            $this->salvarBoletimPneu($idApont, $apont->idAponta, $dadosBolPneu, $dadosItemPneu);
        }
        return 'GRAVOU-APONTAMM';
    }

    private function salvarLog($dados, $pagina) {
        $inserirLogDAO = new InserirLogDAO();
        $inserirLogDAO->salvarDados($dados, $pagina);
    }

    ///////////////////////////////////////////////VERSAO 2/////////////////////////////////////////////////////////////
    //    DADOS QUE VEM DAS PAGINAS INSERIRBOLABERTOMM2, INSERIRBOLFECHADOMM2 E INSERIRAPONTAMM2

    private function salvarBolAberto($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosRendimento, $dadosBolPneu, $dadosItemPneu) {
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
            $this->salvarApont($idBol, $bol->idBoletim, $dadosAponta, $dadosImplemento, $dadosBolPneu, $dadosItemPneu);
            $this->salvarRendimento($idBol, $bol->idBoletim, $dadosRendimento);
        }
    }

    private function salvarBolFechado($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosBolPneu, $dadosItemPneu) {
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

    private function salvarApont($idBolBD, $idBolCel, $dadosAponta, $dadosImplemento, $dadosBolPneu, $dadosItemPneu) {
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

    private function salvarBolAbertoCDC($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosRendimento, $dadosBolPneu, $dadosItemPneu) {
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
            $this->salvarApontCDC($idBol, $bol->idBoletim, $dadosAponta, $dadosImplemento, $dadosBolPneu, $dadosItemPneu);
            $this->salvarRendimentoCDC($idBol, $bol->idBoletim, $dadosRendimento);
        }
    }

    private function salvarBolFechadoCDC($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosBolPneu, $dadosItemPneu) {
        $apontMMDAO = new ApontMMDAO();
        foreach ($dadosAponta as $apont) {
            if ($idBolCel == $apont->idBolAponta) {
                $v = $apontMMDAO->verifApontMMCDC($idBolBD, $apont);
                if ($v == 0) {
                    $apontMMDAO->insApontMMCDC($idBolBD, $apont);
                }
                $idApont = $apontMMDAO->idApontMMCDC($idBolBD, $apont);
                $this->salvarImplementoCDC($idApont, $apont->idAponta, $dadosImplemento);
                $this->salvarBoletimPneuCDC($idApont, $apont->idAponta, $dadosBolPneu, $dadosItemPneu);
            }
        }
    }

    private function salvarApontCDC($idBolBD, $idBolCel, $dadosAponta, $dadosImplemento, $dadosBolPneu, $dadosItemPneu) {
        $apontMMDAO = new ApontMMDAO();
        foreach ($dadosAponta as $apont) {
            if ($idBolCel == $apont->idBolAponta) {
                $v = $apontMMDAO->verifApontMMCDC($idBolBD, $apont);
                if ($v == 0) {
                    $apontMMDAO->insApontMMCDC($idBolBD, $apont);
                }
                $idApont = $apontMMDAO->idApontMMCDC($idBolBD, $apont);
                $this->salvarImplementoCDC($idApont, $apont->idAponta, $dadosImplemento);
                $this->salvarBoletimPneuCDC($idApont, $apont->idAponta, $dadosBolPneu, $dadosItemPneu);
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

    private function salvarBoletimPneuCDC($idApontBD, $idApontCel, $dadosBolPneu, $dadosItemPneu) {
        $boletimPneuDAO = new BoletimPneuDAO();
        foreach ($dadosBolPneu as $bolPneu) {
            if ($idApontCel == $bolPneu->idApontBolPneu) {
                $v = $boletimPneuDAO->verifBoletimPneuCDC($idApontBD, $bolPneu, 1);
                if ($v == 0) {
                    $boletimPneuDAO->insBoletimPneuCDC($idApontBD, $bolPneu, 1);
                }
                $idBolPneu = $boletimPneuDAO->idBoletimPneuCDC($idApontBD, $bolPneu, 1);
                $this->salvarItemMedPneuCDC($idBolPneu, $bolPneu->idBolPneu, $dadosItemPneu);
            }
        }
    }

    private function salvarItemMedPneuCDC($idBolPneuBD, $idBolPneuCel, $dadosItemPneu) {
        $itemMedPneuDAO = new ItemMedPneuDAO();
        foreach ($dadosItemPneu as $itemPneu) {
            if ($idBolPneuCel == $itemPneu->idBolItemMedPneu) {
                $v = $itemMedPneuDAO->verifItemMedPneuCDC($idBolPneuBD, $itemPneu);
                if ($v == 0) {
                    $itemMedPneuDAO->insItemMedPneuCDC($idBolPneuBD, $itemPneu);
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

    private function salvarBolAbertoSDC($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosRendimento, $dadosBolPneu, $dadosItemPneu) {
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
            $this->salvarApontSDC($idBol, $bol->idBoletim, $dadosAponta, $dadosImplemento, $dadosBolPneu, $dadosItemPneu);
            $this->salvarRendimentoSDC($idBol, $bol->idBoletim, $dadosRendimento);
        }
    }

    private function salvarBolFechadoSDC($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosBolPneu, $dadosItemPneu) {
        $apontMMDAO = new ApontMMDAO();
        foreach ($dadosAponta as $apont) {
            if ($idBolCel == $apont->idBolAponta) {
                $v = $apontMMDAO->verifApontMMSDC($idBolBD, $apont);
                if ($v == 0) {
                    $apontMMDAO->insApontMMSDC($idBolBD, $apont);
                }
                $idApont = $apontMMDAO->idApontMMSDC($idBolBD, $apont);
                $this->salvarImplementoSDC($idApont, $apont->idAponta, $dadosImplemento);
                $this->salvarBoletimPneuSDC($idApont, $apont->idAponta, $dadosBolPneu, $dadosItemPneu);
            }
        }
    }

    private function salvarApontSDC($idBolBD, $idBolCel, $dadosAponta, $dadosImplemento, $dadosBolPneu, $dadosItemPneu) {
        $apontMMDAO = new ApontMMDAO();
        foreach ($dadosAponta as $apont) {
            if ($idBolCel == $apont->idBolAponta) {
                $v = $apontMMDAO->verifApontMMSDC($idBolBD, $apont);
                if ($v == 0) {
                    $apontMMDAO->insApontMMSDC($idBolBD, $apont);
                }
                $idApont = $apontMMDAO->idApontMMSDC($idBolBD, $apont);
                $this->salvarImplementoSDC($idApont, $apont->idAponta, $dadosImplemento);
                $this->salvarBoletimPneuSDC($idApont, $apont->idAponta, $dadosBolPneu, $dadosItemPneu);
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

    private function salvarBoletimPneuSDC($idApontBD, $idApontCel, $dadosBolPneu, $dadosItemPneu) {
        $boletimPneuDAO = new BoletimPneuDAO();
        foreach ($dadosBolPneu as $bolPneu) {
            if ($idApontCel == $bolPneu->idApontBolPneu) {
                $v = $boletimPneuDAO->verifBoletimPneuSDC($idApontBD, $bolPneu, 1);
                if ($v == 0) {
                    $boletimPneuDAO->insBoletimPneuSDC($idApontBD, $bolPneu, 1);
                }
                $idBolPneu = $boletimPneuDAO->idBoletimPneuSDC($idApontBD, $bolPneu, 1);
                $this->salvarItemMedPneuSDC($idBolPneu, $bolPneu->idBolPneu, $dadosItemPneu);
            }
        }
    }

    private function salvarItemMedPneuSDC($idBolPneuBD, $idBolPneuCel, $dadosItemPneu) {
        $itemMedPneuDAO = new ItemMedPneuDAO();
        foreach ($dadosItemPneu as $itemPneu) {
            if ($idBolPneuCel == $itemPneu->idBolItemMedPneu) {
                $v = $itemMedPneuDAO->verifItemMedPneuSDC($idBolPneuBD, $itemPneu);
                if ($v == 0) {
                    $itemMedPneuDAO->insItemMedPneuSDC($idBolPneuBD, $itemPneu);
                }
            }
        }
    }

    private function salvarRendimentoSDC($idBolBD, $idBolCel, $dadosRendimento) {
        $rendimentoMMDAO = new RendimentoMMDAO();
        foreach ($dadosRendimento as $rend) {
            if ($idBolCel == $rend->idBolRendimento) {
                $v = $rendimentoMMDAO->verifRendimentoMMSDC($idBolBD, $rend);
                if ($v == 0) {
                    $rendimentoMMDAO->insRendimentoMMSDC($idBolBD, $rend);
                }
            }
        }
    }

}
