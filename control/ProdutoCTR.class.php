<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/dao/ProdutoDAO.class.php');
/**
 * Description of ProdutoCTR
 *
 * @author anderson
 */
class ProdutoCTR {
    //put your code here
    
    public function dados($versao) {
        
        $versao = str_replace("_", ".", $versao);
        
        $produtoDAO = new ProdutoDAO();
        
        if($versao >= 2.00){
        
            $dados = array("dados"=>$produtoDAO->dados());
            $json_str = json_encode($dados);

            return $json_str;
        
        }
        
    }
    
}
