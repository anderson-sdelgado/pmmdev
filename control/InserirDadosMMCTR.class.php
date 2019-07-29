<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('./model/dao/LogDAO.class.php');
require_once('./model/dao/BoletimMMDAO.class.php');
require_once('./model/dao/ApontMMDAO.class.php');
require_once('./model/dao/ImplementoMMDAO.class.php');
require_once('./model/dao/RendimentoMMDAO.class.php');

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

            $bolmm = substr($dados, 0, ($pos1 - 1));
            $apontmm = substr($dados, $pos1, (($pos2 - 1) - $pos1));
            $impl = substr($dados, $pos2);

            $jsonObjBoletim = json_decode($bolmm);
            $jsonObjAponta = json_decode($apontmm);
            $jsonObjImplemento = json_decode($impl);

            $dadosBoletim = $jsonObjBoletim->boletim;
            $dadosAponta = $jsonObjAponta->aponta;
            $dadosImplemento = $jsonObjImplemento->implemento;

            $idBol = $this->salvarBolAberto($dadosBoletim, $dadosAponta, $dadosImplemento);
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

            $apontmm = substr($dados, 0, ($pos1 - 1));
            $imp = substr($dados, $pos1);

            $jsonObjAponta = json_decode($apontmm);
            $jsonObjImplemento = json_decode($imp);

            $dadosAponta = $jsonObjAponta->aponta;
            $dadosImplemento = $jsonObjImplemento->implemento;

            $idBol = $this->salvarApont($dadosAponta, $dadosImplemento);
            
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
                $idBol = $this->salvarApontCDC($dadosAponta, $dadosImplemento);
            } elseif ($pagina == 'insapontmm') {
                $idBol = $this->salvarApontSDC($dadosAponta, $dadosImplemento);
            }
        }

        return 'GRAVOU-APONTAMM';
    }

    public function salvarDadosBolFechadoMM($info, $pagina) {

        $dados = $info['dado'];
        $this->salvarLog($dados, $pagina);

        if ($pagina == 'inserirbolfechadomm2') {

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

            $this->salvarBolFechado($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosRendimento);
        } else {

            $pos1 = strpos($dados, "_") + 1;
            $pos2 = strpos($dados, "|") + 1;
            $pos3 = strpos($dados, "#") + 1;
            $pos4 = strpos($dados, "?") + 1;

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
        $logDAO = new LogDAO();
        $logDAO->salvarDados($dados, $pagina);
    }

    ///////////////////////////////////////////////VERSAO 2/////////////////////////////////////////////////////////////
    //    DADOS QUE VEM DAS PAGINAS INSERIRBOLABERTOMM2, INSERIRBOLFECHADOMM2 E INSERIRAPONTAMM2

    private function salvarBolAberto($dadosBoletim, $dadosAponta, $dadosImplemento) {
        $boletimMMDAO = new BoletimMMDAO();
        foreach ($dadosBoletim as $bol) {
            $v = $boletimMMDAO->verifBoletimMM($bol);
            if ($v == 0) {
                $boletimMMDAO->insBoletimMMAberto($bol);
            }
            $idBol = $boletimMMDAO->idBoletimMM($bol);
            $this->salvarApontBol($idBol, $bol->idBoletim, $dadosAponta, $dadosImplemento);
        }
        return $idBol;
    }

    private function salvarBolFechado($dadosBoletim, $dadosAponta, $dadosImplemento, $dadosRendimento) {
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
            $this->salvarApontBol($idBol, $bol->idBoletim, $dadosAponta, $dadosImplemento);
            $this->salvarRendimento($idBol, $bol->idBoletim, $dadosRendimento);
        }
    }

    private function salvarApont($dadosAponta, $dadosImplemento) {
        $apontMMDAO = new ApontMMDAO();
        foreach ($dadosAponta as $apont) {
            $v = $apontMMDAO->verifApontMM($apont->idExtBolAponta, $apont);
            if ($v == 0) {
                $apontMMDAO->insApontMM($apont->idExtBolAponta, $apont);
            }
            $idApont = $apontMMDAO->idApontMM($apont->idExtBolAponta, $apont);
            $this->salvarImplemento($idApont, $apont->idAponta, $dadosImplemento);
        }
    }

    private function salvarApontBol($idBolBD, $idBolCel, $dadosAponta, $dadosImplemento) {
        $apontMMDAO = new ApontMMDAO();
        foreach ($dadosAponta as $apont) {
            if ($idBolCel == $apont->idBolAponta) {
                $v = $apontMMDAO->verifApontMM($idBolBD, $apont);
                if ($v == 0) {
                    $apontMMDAO->insApontMM($idBolBD, $apont);
                }
                $idApont = $apontMMDAO->idApontMM($idBolBD, $apont);
                $this->salvarImplemento($idApont, $apont->idAponta, $dadosImplemento);
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
            $this->salvarApontBolCDC($idBol, $bol->idBoletim, $dadosAponta, $dadosImplemento);
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
            $idApont = $apontMMDAO->idApontMMCDC($apont->idExtBolAponta, $apont);
            $this->salvarImplementoCDC($idApont, $apont->idAponta, $dadosImplemento);
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
            $this->salvarApontBolSDC($idBol, $bol->idBoletim, $dadosAponta, $dadosImplemento);
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
            $idApont = $apontMMDAO->idApontMMSDC($apont->idExtBolAponta, $apont);
            $this->salvarImplementoSDC($idApont, $apont->idAponta, $dadosImplemento);
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
