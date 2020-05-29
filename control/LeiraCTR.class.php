<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/dao/LeiraDAO.class.php');
/**
 * Description of LeiraCTR
 *
 * @author anderson
 */
class LeiraCTR {
    //put your code here
    
    public function dados($versao) {

        $versao = str_replace("_", ".", $versao);
        
        if($versao >= 2.00){
        
            $leiraDAO = new LeiraDAO();

            $dados = array("dados"=>$leiraDAO->dados());
            $json_str = json_encode($dados);

            return $json_str;
            
        }
        
    }
    
    public function pesqLeiraComp($versao, $info) {

        $versao = str_replace("_", ".", $versao);
        
        $leiraDAO = new LeiraDAO();
        
        if ($versao >= 2.00) {
        
            $jsonObj = json_decode($info['dado']);
            $dados = $jsonObj->dados;

            foreach ($dados as $d) {
                $equip = $d->equip;
                $os = $d->os;
            }

            $retorno = array("dados" => $leiraDAO->retLeiraComp($equip, $os));
            $ret = json_encode($retorno);
            return $ret;
        
        }
        
    }
    
    public function pesqLeiraProd($versao, $info) {

        $versao = str_replace("_", ".", $versao);

        $leiraDAO = new LeiraDAO();
        $carregDAO = new CarregDAO();

        if ($versao >= 2.00) {

            $equip = $info['dado'];
            $retorno = array("dados" => $leiraDAO->retLeiraProd($equip));
            $carregDAO->updCarregProd($equip);
            $ret = json_encode($retorno);
            return $ret;
        }
    }
    
}
