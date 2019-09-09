<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/dao/REquipAtivDAO.class.php');
require_once('../model/dao/ROSAtivDAO.class.php');
require_once('../model/dao/AtividadeDAO.class.php');
require_once('../model/dao/RFuncaoAtivParDAO.class.php');
/**
 * Description of AtividadeCTR
 *
 * @author anderson
 */
class AtividadeCTR {

    //put your code here

    public function dados($versao) {

        $versao = str_replace("_", ".", $versao);
        
        $atividadeDAO = new AtividadeDAO();
        
        if(($versao >= 2.00) && ($versao < 2.01)){
        
            $dados = array("dados" => $atividadeDAO->dadosComFlag());
            $json_str = json_encode($dados);

            return $json_str;
            
        }
        elseif($versao >= 2.01){
            
            $dados = array("dados" => $atividadeDAO->dadosSemFlag());
            $json_str = json_encode($dados);

            return $json_str;
            
        }
        
    }
    
    public function pesq($versao, $info) {

        $versao = str_replace("_", ".", $versao);
        
        if(($versao >= 2.00) && ($versao < 2.01)){
            
            $rEquipAtivDAO = new REquipAtivDAO();
            $rOSAtivDAO = new ROSAtivDAO();
            $atividadeDAO = new AtividadeDAO();

            $dados = $info['dado'];
            $pos1 = strpos($dados, "_") + 1;
            $os = substr($dados, 0, ($pos1 - 1));
            $equip = substr($dados, $pos1);

            $dadosEquipAtiv = array("dados" => $rEquipAtivDAO->dados($equip));
            $resEquipAtiv = json_encode($dadosEquipAtiv);

            $dadosOSAtiv = array("dados" => $rOSAtivDAO->dados($os));
            $resOSAtiv = json_encode($dadosOSAtiv);

            $dadosAtividade = array("dados" => $atividadeDAO->dadosComFlag());
            $resAtividade = json_encode($dadosAtividade);

            return $resEquipAtiv . "_" . $resOSAtiv . "|" . $resAtividade;
        
        }
        elseif($versao >= 2.01){
            
            $rEquipAtivDAO = new REquipAtivDAO();
            $rOSAtivDAO = new ROSAtivDAO();
            $atividadeDAO = new AtividadeDAO();
            $rFuncaoAtivParDAO = new RFuncaoAtivParDAO();

            $dados = $info['dado'];
            $pos1 = strpos($dados, "_") + 1;
            $os = substr($dados, 0, ($pos1 - 1));
            $equip = substr($dados, $pos1);

            $dadosEquipAtiv = array("dados" => $rEquipAtivDAO->dados($equip));
            $resEquipAtiv = json_encode($dadosEquipAtiv);

            $dadosOSAtiv = array("dados" => $rOSAtivDAO->dados($os));
            $resOSAtiv = json_encode($dadosOSAtiv);

            $dadosAtividade = array("dados" => $atividadeDAO->dadosSemFlag());
            $resAtividade = json_encode($dadosAtividade);
            
            $dadosRFuncaoAtivPar = array("dados" => $rFuncaoAtivParDAO->dados());
            $resRFuncaoAtivPar = json_encode($dadosRFuncaoAtivPar);
            

            return $resEquipAtiv . "_" . $resOSAtiv . "|" . $resAtividade . "#" . $resRFuncaoAtivPar;
            
        }
        
    }

}
