<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../control/AtualAplicCTR.class.php');
require_once('../model/AtividadeDAO.class.php');
require_once('../model/AtualAplicDAO.class.php');
require_once('../model/BocalDAO.class.php');
require_once('../model/ComponenteDAO.class.php');
require_once('../model/EquipDAO.class.php');
require_once('../model/FrenteDAO.class.php');
require_once('../model/FuncionarioDAO.class.php');
require_once('../model/ItemOSMecanDAO.class.php');
require_once('../model/LeiraDAO.class.php');
require_once('../model/OSDAO.class.php');
require_once('../model/ParadaDAO.class.php');
require_once('../model/PerdaDAO.class.php');
require_once('../model/PlantioDAO.class.php');
require_once('../model/PneuDAO.class.php');
require_once('../model/PressaoBocalDAO.class.php');
require_once('../model/ProdutoDAO.class.php');
require_once('../model/PropriedadeDAO.class.php');
require_once('../model/RAtivParadaDAO.class.php');
require_once('../model/REquipAtivDAO.class.php');
require_once('../model/REquipPneuDAO.class.php');
require_once('../model/ROSAtivDAO.class.php');
require_once('../model/RFuncaoAtivParDAO.class.php');
require_once('../model/ServicoDAO.class.php');
require_once('../model/TipoFrenteDAO.class.php');
require_once('../model/TurnoDAO.class.php');
/**
 * Description of BaseDadosCTR
 *
 * @author anderson
 */
class BaseDadosCTR {

    public function dadosAtiv($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){
        
            $atividadeDAO = new AtividadeDAO();

            $dados = array("dados" => $atividadeDAO->dados());
            $retJson = json_encode($dados);

            return $retJson;
        
        }

    }
    
    public function pesqAtiv($info) {

        $rEquipAtivDAO = new REquipAtivDAO();
        $rOSAtivDAO = new ROSAtivDAO();
        $atividadeDAO = new AtividadeDAO();
        $rFuncaoAtivParDAO = new RFuncaoAtivParDAO();
        $atualAplicDAO = new AtualAplicDAO();

        $jsonObj = json_decode($info['dado']);
        $dados = $jsonObj->dados;

        foreach ($dados as $d) {
            $idEquip = $d->idEquip;
            $nroOS = $d->nroOS;
            $token = $d->token;
        }

        $v = $atualAplicDAO->verToken($token);
        
        if ($v > 0) {
            
            $dadosREquipAtiv = array("dados" => $rEquipAtivDAO->pesqIdEquip($idEquip));
            $resREquipAtiv = json_encode($dadosREquipAtiv);

            $dadosROSAtiv = array("dados" => $rOSAtivDAO->pesq($nroOS));
            $resROSAtiv = json_encode($dadosROSAtiv);

            $dadosAtividade = array("dados" => $atividadeDAO->dados());
            $resAtividade = json_encode($dadosAtividade);

            $dadosRFuncaoAtivPar = array("dados" => $rFuncaoAtivParDAO->dados());
            $resRFuncaoAtivPar = json_encode($dadosRFuncaoAtivPar);

            return $resREquipAtiv . "_" . $resROSAtiv . "_" . $resAtividade . "_" . $resRFuncaoAtivPar;
            
        }
  
    }

    public function pesqECMAtiv($info) {

        $rEquipAtivDAO = new REquipAtivDAO();
        $atividadeDAO = new AtividadeDAO();
        $rFuncaoAtivParDAO = new RFuncaoAtivParDAO();
        $atualAplicDAO = new AtualAplicDAO();

        $jsonObj = json_decode($info['dado']);
        $dados = $jsonObj->dados;

        foreach ($dados as $d) {
            $idEquip = $d->idEquip;
            $token = $d->token;
        }

        $v = $atualAplicDAO->verToken($token);
        
        if ($v > 0) {
        
            $dadosREquipAtiv = array("dados" => $rEquipAtivDAO->pesqIdEquip($idEquip));
            $resREquipAtiv = json_encode($dadosREquipAtiv);

            $dadosAtividade = array("dados" => $atividadeDAO->dados());
            $resAtividade = json_encode($dadosAtividade);

            $dadosRFuncaoAtivPar = array("dados" => $rFuncaoAtivParDAO->dados());
            $resRFuncaoAtivPar = json_encode($dadosRFuncaoAtivPar);

            return $resREquipAtiv . "_" . $resAtividade . "_" . $resRFuncaoAtivPar;
        
        }

    }
    
    public function dadosBocal($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){
        
            $bocalDAO = new BocalDAO();

            $dados = array("dados"=>$bocalDAO->dados());
            $json_str = json_encode($dados);

            return $json_str;

        }
    }
        
    public function dadosComponente($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){
        
            $componenteDAO = new ComponenteDAO();

            $dados = array("dados"=>$componenteDAO->dados());
            $json_str = json_encode($dados);

            return $json_str;
                
        }
        
    }
    
    public function dadosEquip($info) {

        $equipDAO = new EquipDAO();
        $rEquipAtivDAO = new REquipAtivDAO();
        $atualAplicCTR = new AtualAplicCTR();

        $jsonObj = json_decode($info['dado']);
        $dados = $jsonObj->dados;

        foreach ($dados as $d) {
            $nroEquip = $d->nroEquip;
            $versao = $d->versao;
            $aplic = $d->aplic;
        }
        
        $dadosEquip = array("dados" => $equipDAO->dados($nroEquip));
        $resEquip = json_encode($dadosEquip);

        $dadosREquipAtivDAO = array("dados" => $rEquipAtivDAO->pesqNroEquip($nroEquip));
        $resREquipAtivDAO = json_encode($dadosREquipAtivDAO);

        $v = $equipDAO->verifEquipNro($nroEquip);
        if ($v > 0) {
            $atualAplicCTR->inserirAtualVersao($equipDAO->retEquipNro($nroEquip), $versao, $aplic);
        }
        
        return $resEquip . "_" . $resREquipAtivDAO;

    }
    
    public function dadosEquipSeg($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){
        
            $equipDAO = new EquipDAO();

            $dados = array("dados" => $equipDAO->dadosSeg());
            $json_str = json_encode($dados);

            return $json_str;

        }
        
    }
        
    public function dadosEquipPneu($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){
        
            $equipDAO = new EquipDAO();

            $dados = array("dados" => $equipDAO->dadosPneu());
            $json_str = json_encode($dados);

            return $json_str;

        }
        
    }
    
    public function dadosFrente($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){
        
            $frenteDAO = new FrenteDAO();

            $dados = array("dados"=>$frenteDAO->dados());
            $json_str = json_encode($dados);

            return $json_str;
                
        }
        
    }
    
    public function dadosFunc($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){
        
            $funcionarioDAO = new FuncionarioDAO();

            $dados = array("dados" => $funcionarioDAO->dados());
            $json_str = json_encode($dados);

            return $json_str;
        
        }
        
    }
    
    public function dadosItemOSMecan($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){
            
            $itemOSMecanDAO = new ItemOSMecanDAO();

            $dados = array("dados"=>$itemOSMecanDAO->dados());
            $json_str = json_encode($dados);

            return $json_str;

        }

    }
    
    public function dadosLeira($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){
            
            $leiraDAO = new LeiraDAO();

            $dados = array("dados"=>$leiraDAO->dados());
            $json_str = json_encode($dados);

            return $json_str;

        }
        
    }
    
    public function pesqOS($info) {

        $osDAO = new OSDAO();
        $rOSAtivDAO = new ROSAtivDAO();
        $atualAplicDAO = new AtualAplicDAO();

        $jsonObj = json_decode($info['dado']);
        $dados = $jsonObj->dados;

        foreach ($dados as $d) {
            $nroOS = $d->nroOS;
            $token = $d->token;
        }

        $v = $atualAplicDAO->verToken($token);
        
        if ($v > 0) {

            $dadosOS = array("dados" => $osDAO->pesq($nroOS));
            $resOS = json_encode($dadosOS);

            $dadosROSAtiv = array("dados" => $rOSAtivDAO->pesq($nroOS));
            $resROSAtiv = json_encode($dadosROSAtiv);

            return $resOS . "_" . $resROSAtiv;
        
        }
        
    }
        
    public function pesqOSMecan($info) {

        $osDAO = new OSDAO();
        $itemOSMecanDAO = new ItemOSMecanDAO();
        $atualAplicDAO = new AtualAplicDAO();

        $jsonObj = json_decode($info['dado']);
        $dados = $jsonObj->dados;

        foreach ($dados as $d) {
            $nroOS = $d->nroOS;
            $idEquip = $d->idEquip;
            $token = $d->token;
        }

        $v = $atualAplicDAO->verToken($token);
        
        if ($v > 0) {

            $dadosOS = array("dados" => $osDAO->pesqMecan($nroOS, $idEquip));
            $resOS = json_encode($dadosOS);

            $dadosItemOSMecan = array("dados" => $itemOSMecanDAO->pesq($nroOS, $idEquip));
            $resItemOSMecan = json_encode($dadosItemOSMecan);

            return $resOS . "_" . $resItemOSMecan;
        
        }

    }
    
    public function dadosOS($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){
            
            $osDAO = new OSDAO();

            $dadosOS = array("dados" => $osDAO->dados());
            $resOS = json_encode($dadosOS);

            return $resOS;
        
        }

    }
    
    public function dadosParada($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){
            
            $paradaDAO = new ParadaDAO();

            $dados = array("dados" => $paradaDAO->dados());
            $json_str = json_encode($dados);

            return $json_str;
        
        }

    }

    public function pesqPneu($info) {

        $pneuDAO = new PneuDAO();
        $atualAplicDAO = new AtualAplicDAO();

        $jsonObj = json_decode($info['dado']);
        $dados = $jsonObj->dados;

        foreach ($dados as $d) {
            $codPneu = $d->codPneu;
            $token = $d->token;
        }

        $v = $atualAplicDAO->verToken($token);
        
        if ($v > 0) {
            
            $dadosPneu = array("dados" => $pneuDAO->pesq($codPneu));
            $resPneu = json_encode($dadosPneu);

            return $resPneu;
        
        }

    }
    
    public function dadosPressaoBocal($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){
            
            $pressaoBocalDAO = new PressaoBocalDAO();

            $dados = array("dados" => $pressaoBocalDAO->dados());
            $json_str = json_encode($dados);

            return $json_str;
        
        }
        
    }
    
    public function dadosProduto($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){
            
            $produtoDAO = new ProdutoDAO();

            $dados = array("dados"=>$produtoDAO->dados());
            $json_str = json_encode($dados);

            return $json_str;
        
        }

    }
    
    public function dadosPropriedade($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){
            
            $propriedadeDAO = new PropriedadeDAO();

            $dados = array("dados"=> $propriedadeDAO->dados());
            $json_str = json_encode($dados);

            return $json_str;
        
        }

    }
    
    public function dadosRAtivParada($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){
            
            $rAtivParadaDAO = new RAtivParadaDAO();

            $dados = array("dados"=>$rAtivParadaDAO->dados());
            $json_str = json_encode($dados);

            return $json_str;
                    
        }

    }
        
    public function dadosREquipPneu($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){
            
            $rEquipPneuDAO = new REquipPneuDAO();

            $dados = array("dados"=>$rEquipPneuDAO->dados());
            $json_str = json_encode($dados);

            return $json_str;
                    
        }

    }
    
    public function dadosRFuncaoAtivPar($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){
            
            $rFuncaoAtivParDAO = new RFuncaoAtivParDAO();

            $dados = array("dados"=>$rFuncaoAtivParDAO->dados());
            $json_str = json_encode($dados);

            return $json_str;
        
        }

    }
                
    public function dadosROSAtiv($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){
            
            $rOSAtivDAO = new ROSAtivDAO();

            $dados = array("dados"=>$rOSAtivDAO->dadosECM());
            $json_str = json_encode($dados);

            return $json_str;
        
        }

    }
    
    public function dadosServico($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){
            
            $servicoDAO = new ServicoDAO();

            $dados = array("dados"=>$servicoDAO->dados());
            $json_str = json_encode($dados);

            return $json_str;
        
        }

    }
    
    public function dadosTurno($info) {

        $atualAplicCTR = new AtualAplicCTR();
        
        if($atualAplicCTR->verifToken($info)){
            
            $turnoDAO = new TurnoDAO();

            $dados = array("dados"=>$turnoDAO->dados());
            $json_str = json_encode($dados);

            return $json_str;
        
        }
        
    }

    
}
