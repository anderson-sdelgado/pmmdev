<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/AtividadeDAO.class.php');
require_once('../model/BocalDAO.class.php');
require_once('../model/ComponenteDAO.class.php');
require_once('../model/EquipDAO.class.php');
require_once('../model/EquipSegDAO.class.php');
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

    public function dadosAtiv() {

        $atividadeDAO = new AtividadeDAO();

        $dados = array("dados" => $atividadeDAO->dados());
        $retJson = json_encode($dados);

        return $retJson;

    }
    
    public function pesqAtiv($info) {

        $rEquipAtivDAO = new REquipAtivDAO();
        $rOSAtivDAO = new ROSAtivDAO();
        $atividadeDAO = new AtividadeDAO();
        $rFuncaoAtivParDAO = new RFuncaoAtivParDAO();

        $array = explode("_", $info);

        $dadosEquipAtiv = array("dados" => $rEquipAtivDAO->dados($array[1]));
        $resEquipAtiv = json_encode($dadosEquipAtiv);

        $dadosOSAtiv = array("dados" => $rOSAtivDAO->pesq($array[0]));
        $resOSAtiv = json_encode($dadosOSAtiv);

        $dadosAtividade = array("dados" => $atividadeDAO->dados());
        $resAtividade = json_encode($dadosAtividade);

        $dadosRFuncaoAtivPar = array("dados" => $rFuncaoAtivParDAO->dados());
        $resRFuncaoAtivPar = json_encode($dadosRFuncaoAtivPar);

        return $resEquipAtiv . "_" . $resOSAtiv . "_" . $resAtividade . "_" . $resRFuncaoAtivPar;
  
    }

    public function pesqECMAtiv($info) {

        $rEquipAtivDAO = new REquipAtivDAO();
        $osDAO = new OSDAO();
        $atividadeDAO = new AtividadeDAO();

        $array = explode("_", $info);

        $dadosEquipAtiv = array("dados" => $rEquipAtivDAO->dados($array[1]));
        $resEquipAtiv = json_encode($dadosEquipAtiv);

        $dadosOSAtiv = array("dados" => $osDAO->dadosECM($array[0]));
        $resOSAtiv = json_encode($dadosOSAtiv);

        $dadosAtividade = array("dados" => $atividadeDAO->dados());
        $resAtividade = json_encode($dadosAtividade);

        return $resEquipAtiv . "_" . $resOSAtiv . "_" . $resAtividade;

    }
    
    public function dadosBocal() {

        $bocalDAO = new BocalDAO();

        $dados = array("dados"=>$bocalDAO->dados());
        $json_str = json_encode($dados);

        return $json_str;

    }
        
    public function dadosComponente() {

        $componenteDAO = new ComponenteDAO();

        $dados = array("dados"=>$componenteDAO->dados());
        $json_str = json_encode($dados);

        return $json_str;

    }
    
    public function dadosEquip($info) {

        $equipDAO = new EquipDAO();
        $rEquipAtivDAO = new REquipAtivDAO();
        $rEquipPneuDAO = new REquipPneuDAO();

        $dado = $info['dado'];

        $dadosEquip = array("dados" => $equipDAO->dados($dado));
        $resEquip = json_encode($dadosEquip);

        $dadosREquipAtivDAO = array("dados" => $rEquipAtivDAO->dados($dado));
        $resREquipAtivDAO = json_encode($dadosREquipAtivDAO);

        $dadosREquipPneuDAO = array("dados" => $rEquipPneuDAO->dados($dado));
        $resREquipPneuDAO = json_encode($dadosREquipPneuDAO);

        return $resEquip . "_" . $resREquipAtivDAO . "_" . $resREquipPneuDAO;

    }
    
    public function dadosEquipSeg() {

        $equipSegDAO = new EquipSegDAO();

        $dados = array("dados" => $equipSegDAO->dados());
        $json_str = json_encode($dados);

        return $json_str;

    }
    
    public function dadosECMEquipSeg() {

        $equipSegDAO = new EquipSegDAO();

        $dados = array("dados" => $equipSegDAO->dadosECM());
        $json_str = json_encode($dados);

        return $json_str;

    }
    
    public function dadosFrente() {

        $frenteDAO = new FrenteDAO();

        $dados = array("dados"=>$frenteDAO->dados());
        $json_str = json_encode($dados);

        return $json_str;

    }
    
    public function dadosFunc() {

        $funcionarioDAO = new FuncionarioDAO();

        $dados = array("dados" => $funcionarioDAO->dados());
        $json_str = json_encode($dados);

        return $json_str;

    }
    
    public function dadosInfor($info) {

        $dado = $info['dado'];

        $tipoFrenteDAO = new TipoFrenteDAO();
        $plantioDAO = new PlantioDAO();
        $perdaDAO = new PerdaDAO();

        $tipoFrente = $tipoFrenteDAO->dados($dado);

        if($tipoFrente == 1) {
            $dadosPlantio = array("dados" => $plantioDAO->dados($dado));
            $retorno = json_encode($dadosPlantio);
        }
        else if($tipoFrente == 3) {
            $dadosPerda = array("dados" => $perdaDAO->dados($dado));
            $retorno = json_encode($dadosPerda);
        }
        else{
            $retorno = "";
        }

        return $tipoFrente . "_" . $retorno;

    }
    
    public function dadosItemOSMecan() {

        $itemOSMecanDAO = new ItemOSMecanDAO();

        $dados = array("dados"=>$itemOSMecanDAO->dados());
        $json_str = json_encode($dados);

        return $json_str;

    }
    
    public function dadosLeira() {

        $leiraDAO = new LeiraDAO();

        $dados = array("dados"=>$leiraDAO->dados());
        $json_str = json_encode($dados);

        return $json_str;

    }
    
    public function pesqOS($info) {

        $osDAO = new OSDAO();
        $rOSAtivDAO = new ROSAtivDAO();

        $dado = $info['dado'];

        $dadosOS = array("dados" => $osDAO->pesq($dado));
        $resOS = json_encode($dadosOS);

        $dadosROSAtiv = array("dados" => $rOSAtivDAO->pesq($dado));
        $resROSAtiv = json_encode($dadosROSAtiv);

        return $resOS . "_" . $resROSAtiv;
        
    }
        
    public function pesqOSMecan($info) {

        $osDAO = new OSDAO();
        $itemOSMecanDAO = new ItemOSMecanDAO();

        $dado = $info['dado'];
        $array = explode("_", $dado);

        $dadosOS = array("dados" => $osDAO->pesqMecan($array[0], $array[1]));
        $resOS = json_encode($dadosOS);

        $dadosItemOSMecan = array("dados" => $itemOSMecanDAO->pesq($array[0], $array[1]));
        $resItemOSMecan = json_encode($dadosItemOSMecan);

        return $resOS . "_" . $resItemOSMecan;

    }
    
    public function dadosOS() {

        $osDAO = new OSDAO();

        $dadosOS = array("dados" => $osDAO->dados());
        $resOS = json_encode($dadosOS);

        return $resOS;

    }
    
    public function dadosParada() {

        $paradaDAO = new ParadaDAO();

        $dados = array("dados" => $paradaDAO->dados());
        $json_str = json_encode($dados);

        return $json_str;

    }
    
    public function atualParada() {

        $rAtivParadaDAO = new RAtivParadaDAO();
        $paradaDAO = new ParadaDAO();

        $dadosRAtivParadaDAO = array("dados" => $rAtivParadaDAO->dados());
        $resRAtivParadaDAO = json_encode($dadosRAtivParadaDAO);

        $dadosParada = array("dados" => $paradaDAO->dados());
        $resParada = json_encode($dadosParada);

        return $resRAtivParadaDAO . "_" . $resParada;
 
    }
    
    public function dadosPerda($info) {

        $dado = $info['dado'];

        $perdaDAO = new PerdaDAO();

        $dadosPerda = array("dados" => $perdaDAO->dados($dado));
        $resPerda = json_encode($dadosPerda);

        return $resPerda;

    }
    
    public function dadosPneu() {

        $pneuDAO = new PneuDAO();

        $dados = array("dados" => $pneuDAO->dados());
        $json_str = json_encode($dados);

        return $json_str;

    }
    
    public function pesqPneu($info) {

        $pneuDAO = new PneuDAO();

        $dado = $info['dado'];

        $dadosPneu = array("dados" => $pneuDAO->pesq($dado));
        $resPneu = json_encode($dadosPneu);

        return $resPneu;

    }
    
    public function dadosPressaoBocal() {

        $pressaoBocalDAO = new PressaoBocalDAO();

        $dados = array("dados" => $pressaoBocalDAO->dados());
        $json_str = json_encode($dados);

        return $json_str;
        
    }
    
    public function dadosProduto() {

        $produtoDAO = new ProdutoDAO();

        $dados = array("dados"=>$produtoDAO->dados());
        $json_str = json_encode($dados);

        return $json_str;

    }
    
    public function dadosPropriedade() {

        $propriedadeDAO = new PropriedadeDAO();

        $dados = array("dados"=> $propriedadeDAO->dados());
        $json_str = json_encode($dados);

        return $json_str;

    }
    
    public function dadosRAtivParada() {

        $rAtivParadaDAO = new RAtivParadaDAO();

        $dados = array("dados"=>$rAtivParadaDAO->dados());
        $json_str = json_encode($dados);

        return $json_str;

    }
    
    public function dadosRFuncaoAtivPar() {

        $rFuncaoAtivParDAO = new RFuncaoAtivParDAO();

        $dados = array("dados"=>$rFuncaoAtivParDAO->dados());
        $json_str = json_encode($dados);

        return $json_str;

    }
                
    public function dadosROSAtiv() {

        $rOSAtivDAO = new ROSAtivDAO();

        $dados = array("dados"=>$rOSAtivDAO->dadosECM());
        $json_str = json_encode($dados);

        return $json_str;

    }
    
    public function dadosServico() {

        $servicoDAO = new ServicoDAO();

        $dados = array("dados"=>$servicoDAO->dados());
        $json_str = json_encode($dados);

        return $json_str;

    }
    
    public function dadosTurno() {

        $turnoDAO = new TurnoDAO();

        $dados = array("dados"=>$turnoDAO->dados());
        $json_str = json_encode($dados);

        return $json_str;

    }
    
}
