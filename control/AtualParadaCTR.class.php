<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require('./model/dao/RAtivParadaDAO.class.php');
require('./model/dao/ParadaDAO.class.php');
/**
 * Description of AtualParada
 *
 * @author anderson
 */
class AtualParadaCTR {

    //put your code here

    public function dados($info) {

        $rAtivParadaDAO = new RAtivParadaDAO();
        $paradaDAO = new ParadaDAO();

        $dado = $info['dado'];

        $dadosAtivParada = array("dados" => $rAtivParadaDAO->dados($dado));
        $resAtivParada = json_encode($dadosAtivParada);

        $dadosParada = array("dados" => $paradaDAO->dados());
        $resParada = json_encode($dadosParada);
        
        return $resAtivParada . "_" . $resParada;
                
    }

}
