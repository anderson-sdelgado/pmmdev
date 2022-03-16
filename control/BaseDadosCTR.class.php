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
require_once('../model/OSMecanDAO.class.php');
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
    //put your code here
    
    private $base = 2;
    
    public function dadosAtiv($versao) {

        $versao = str_replace("_", ".", $versao);
        
        $atividadeDAO = new AtividadeDAO();
        
        if($versao >= 4.00){
            
            $dados = array("dados" => $atividadeDAO->dados($this->base));
            $retJson = json_encode($dados);

            return $retJson;
            
        }
        
    }
    
    public function pesqAtiv($versao, $info) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 4.00){
            
            $rEquipAtivDAO = new REquipAtivDAO();
            $rOSAtivDAO = new ROSAtivDAO();
            $atividadeDAO = new AtividadeDAO();
            $rFuncaoAtivParDAO = new RFuncaoAtivParDAO();

            $dados = $info['dado'];
            $pos1 = strpos($dados, "_") + 1;
            $os = substr($dados, 0, ($pos1 - 1));
            $equip = substr($dados, $pos1);

            $dadosEquipAtiv = array("dados" => $rEquipAtivDAO->dados($equip, $this->base));
            $resEquipAtiv = json_encode($dadosEquipAtiv);

            $dadosOSAtiv = array("dados" => $rOSAtivDAO->dados($os, $this->base));
            $resOSAtiv = json_encode($dadosOSAtiv);

            $dadosAtividade = array("dados" => $atividadeDAO->dados($this->base));
            $resAtividade = json_encode($dadosAtividade);
            
            $dadosRFuncaoAtivPar = array("dados" => $rFuncaoAtivParDAO->dados($this->base));
            $resRFuncaoAtivPar = json_encode($dadosRFuncaoAtivPar);

            return $resEquipAtiv . "_" . $resOSAtiv . "|" . $resAtividade . "#" . $resRFuncaoAtivPar;
            
        }
        
    }

    public function pesqECMAtiv($versao, $info) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 4.00){
            
            $rEquipAtivDAO = new REquipAtivDAO();
            $osDAO = new OSDAO();
            $atividadeDAO = new AtividadeDAO();

            $dados = $info['dado'];
            $pos1 = strpos($dados, "_") + 1;
            $os = substr($dados, 0, ($pos1 - 1));
            $equip = substr($dados, $pos1);

            $dadosEquipAtiv = array("dados" => $rEquipAtivDAO->dados($equip, $this->base));
            $resEquipAtiv = json_encode($dadosEquipAtiv);

            $dadosOSAtiv = array("dados" => $osDAO->dadosECM($os, $this->base));
            $resOSAtiv = json_encode($dadosOSAtiv);

            $dadosAtividade = array("dados" => $atividadeDAO->dados($this->base));
            $resAtividade = json_encode($dadosAtividade);

            return $resEquipAtiv . "_" . $resOSAtiv . "|" . $resAtividade;
        
        }
        
    }
    
    public function dadosBocal($versao) {
        
        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 4.00){
        
            $bocalDAO = new BocalDAO();

            $dados = array("dados"=>$bocalDAO->dados($this->base));
            $json_str = json_encode($dados);

            return $json_str;
        
        }
        
    }
        
    public function dadosComponente($versao) {
        
        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 4.00){
        
            $componenteDAO = new ComponenteDAO();

            $dados = array("dados"=>$componenteDAO->dados($this->base));
            $json_str = json_encode($dados);

            return $json_str;
        
        }
        
    }
    
    public function dadosEquip($versao, $info) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 4.00){
        
            $equipDAO = new EquipDAO();
            $rEquipAtivDAO = new REquipAtivDAO();
            $rEquipPneuDAO = new REquipPneuDAO();

            $dado = $info['dado'];

            $dadosEquip = array("dados" => $equipDAO->dados($dado, $this->base));
            $resEquip = json_encode($dadosEquip);

            $dadosREquipAtivDAO = array("dados" => $rEquipAtivDAO->dados($dado, $this->base));
            $resREquipAtivDAO = json_encode($dadosREquipAtivDAO);
            
            $dadosREquipPneuDAO = array("dados" => $rEquipPneuDAO->dados($dado, $this->base));
            $resREquipPneuDAO = json_encode($dadosREquipPneuDAO);

            return $resEquip . "#" . $resREquipAtivDAO . "_" . $resREquipPneuDAO;
        
        }
        
    }
    
    public function dadosECMEquip($versao, $info) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 4.00){
        
            $equipDAO = new EquipDAO();
            $rEquipAtivDAO = new REquipAtivDAO();
            $rEquipPneuDAO = new REquipPneuDAO();

            $dado = $info['dado'];

            $dadosEquip = array("dados" => $equipDAO->dadosECM($dado, $this->base));
            $resEquip = json_encode($dadosEquip);

            $dadosREquipAtivDAO = array("dados" => $rEquipAtivDAO->dados($dado, $this->base));
            $resREquipAtivDAO = json_encode($dadosREquipAtivDAO);
            
            $dadosREquipPneuDAO = array("dados" => $rEquipPneuDAO->dados($dado, $this->base));
            $resREquipPneuDAO = json_encode($dadosREquipPneuDAO);

            return $resEquip . "#" . $resREquipAtivDAO . "_" . $resREquipPneuDAO;
        
        }
        
    }
    
    public function dadosEquipSeg($versao) {
        
        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 4.00){

            $equipSegDAO = new EquipSegDAO();

            $dados = array("dados" => $equipSegDAO->dados($this->base));
            $json_str = json_encode($dados);

            return $json_str;
        
        }
        
    }
    
    public function dadosECMEquipSeg($versao) {
        
        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 4.00){

            $equipSegDAO = new EquipSegDAO();

            $dados = array("dados" => $equipSegDAO->dadosECM($this->base));
            $json_str = json_encode($dados);

            return $json_str;
        
        }
        
    }
    
    public function dadosFrente($versao) {
        
        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 4.00){
        
            $frenteDAO = new FrenteDAO();

            $dados = array("dados"=>$frenteDAO->dados($this->base));
            $json_str = json_encode($dados);

            return $json_str;
        
        }
        
    }
    
    public function dadosFunc($versao) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 4.00){
        
            $funcionarioDAO = new FuncionarioDAO();

            $dados = array("dados" => $funcionarioDAO->dados($this->base));
            $json_str = json_encode($dados);

            return $json_str;
        
        }
        
    }
    
    public function dadosInfor($versao, $info) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 4.00){
        
            $dado = $info['dado'];

            $tipoFrenteDAO = new TipoFrenteDAO();
            $plantioDAO = new PlantioDAO();
            $perdaDAO = new PerdaDAO();
            
            $tipoFrente = $tipoFrenteDAO->dados($dado, $this->base);
            
            if($tipoFrente == 1) {
                $dadosPlantio = array("dados" => $plantioDAO->dados($dado, $this->base));
                $retorno = json_encode($dadosPlantio);
            }
            else if($tipoFrente == 3) {
                $dadosPerda = array("dados" => $perdaDAO->dados($dado, $this->base));
                $retorno = json_encode($dadosPerda);
            }
            else{
                $retorno = "";
            }

            return "tipo=" . $tipoFrente . "_" . $retorno;
        
        }
        
    }
    
    public function dadosLeira($versao) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 4.00){
        
            $leiraDAO = new LeiraDAO();

            $dados = array("dados"=>$leiraDAO->dados($this->base));
            $json_str = json_encode($dados);

            return $json_str;
            
        }
        
    }
    
    public function dadosOS($versao, $info) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 4.00){
        
            $osDAO = new OSDAO();
            $rOSAtivDAO = new ROSAtivDAO();

            $dado = $info['dado'];

            $dadosOS = array("dados" => $osDAO->dados($dado, $this->base));
            $resOS = json_encode($dadosOS);

            $dadosROSAtiv = array("dados" => $rOSAtivDAO->dados($dado, $this->base));
            $resROSAtiv = json_encode($dadosROSAtiv);

            return $resOS . "#" . $resROSAtiv;
        
        }
        
    }
    
    public function dadosOSMecan($versao, $info) {

        $versao = str_replace("_", ".", $versao);
       
        if($versao >= 2.00){
        
            $osMecanDAO = new OSMecanDAO();
            $itemOSMecanDAO = new ItemOSMecanDAO();

            $dado = $info['dado'];

            $osDados = array("dados" => $osMecanDAO->dados($dado, $this->base));
            $resOS = json_encode($osDados);

            $dadosItemOS = array("dados" => $itemOSMecanDAO->dados($dado, $this->base));
            $resItemOS = json_encode($dadosItemOS);

            return $resOS . "#" . $resItemOS;

        }
        
    }
    
    public function pesqECMOS($versao, $info) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 4.00){
        
            $osDAO = new OSDAO();
            $rOSAtivDAO = new ROSAtivDAO();
            
            $dado = $info['dado'];

            $dadosOS = array("dados" => $osDAO->dadosECM($dado, $this->base));
            $resOS = json_encode($dadosOS);
            
            $dadosROSAtiv = array("dados" => $rOSAtivDAO->dados($dado, $this->base));
            $resROSAtiv = json_encode($dadosROSAtiv);

            return $resOS . "#" . $resROSAtiv;
        
        }
        
    }
    
    public function dadosECMOS($versao) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 4.00){
        
            $osDAO = new OSDAO();
            
            $dadosOS = array("dados" => $osDAO->dadosClearECM($this->base));
            $resOS = json_encode($dadosOS);

            return $resOS;
        
        }
        
    }
    
    public function dadosParada($versao) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 4.00){
        
            $paradaDAO = new ParadaDAO();

            $dados = array("dados" => $paradaDAO->dados($this->base));
            $json_str = json_encode($dados);

            return $json_str;
        
        }
        
    }
    
    public function atualParada($versao) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 4.00){
            
            $rAtivParadaDAO = new RAtivParadaDAO();
            $paradaDAO = new ParadaDAO();

            $dadosRAtivParadaDAO = array("dados" => $rAtivParadaDAO->dados($this->base));
            $resRAtivParadaDAO = json_encode($dadosRAtivParadaDAO);

            $dadosParada = array("dados" => $paradaDAO->dados($this->base));
            $resParada = json_encode($dadosParada);

            return $resRAtivParadaDAO . "_" . $resParada;
        
        }
                
    }
    
    public function dadosPerda($versao, $info) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 4.00){
        
            $dado = $info['dado'];

            $perdaDAO = new PerdaDAO();
            
            $dadosPerda = array("dados" => $perdaDAO->dados($dado, $this->base));
            $resPerda = json_encode($dadosPerda);

            return $resPerda;
        
        }
        
    }
    
    public function dadosPneu($versao, $info) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 4.00){
        
            $pneuDAO = new PneuDAO();

            $dado = $info['dado'];

            $dadosPneu = array("dados" => $pneuDAO->dados($dado, $this->base));
            $resPneu = json_encode($dadosPneu);

            return $resPneu;
        
        }
        
    }
    
    public function dadosPressaoBocal($versao) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 4.00){

            $pressaoBocalDAO = new PressaoBocalDAO();

            $dados = array("dados" => $pressaoBocalDAO->dados($this->base));
            $json_str = json_encode($dados);

            return $json_str;
        
        }
    
    }
    
    public function dadosProduto($versao) {
        
        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 4.00){
        
            $produtoDAO = new ProdutoDAO();
            
            $dados = array("dados"=>$produtoDAO->dados($this->base));
            $json_str = json_encode($dados);

            return $json_str;
        
        }
        
    }
    
    public function dadosPropriedade($versao) {
        
        $versao = str_replace("_", ".", $versao);
        
        $propriedadeDAO = new PropriedadeDAO();
        
        if($versao >= 4.00){
        
            $dados = array("dados"=> $propriedadeDAO->dados($this->base));
            $json_str = json_encode($dados);

            return $json_str;
        
        }
        
    }
    
    public function dadosRAtivParada($versao) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 4.00){
        
            $rAtivParadaDAO = new RAtivParadaDAO();

            $dados = array("dados"=>$rAtivParadaDAO->dados($this->base));
            $json_str = json_encode($dados);

            return $json_str;
        
        }
        
    }
    
    public function dadosRFuncaoAtivPar($versao) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 4.00){
        
            $rFuncaoAtivParDAO = new RFuncaoAtivParDAO();

            $dados = array("dados"=>$rFuncaoAtivParDAO->dados($this->base));
            $json_str = json_encode($dados);

            return $json_str;
        
        }
        
    }
            
    public function dadosServico($versao) {
        
        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 4.00){
        
            $servicoDAO = new ServicoDAO();

            $dados = array("dados"=>$servicoDAO->dados($this->base));
            $json_str = json_encode($dados);

            return $json_str;
        
        }
        
    }
    
    public function dadosTurno($versao) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 4.00){
        
            $turnoDAO = new TurnoDAO();

            $dados = array("dados"=>$turnoDAO->dados($this->base));
            $json_str = json_encode($dados);

            return $json_str;
            
        }
        
    }
    
}
