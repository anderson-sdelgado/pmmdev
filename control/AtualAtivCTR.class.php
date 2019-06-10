<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require('./model/dao/REquipAtivDAO.class.php');
require('./model/dao/ROSAtivDAO.class.php');
require('./model/dao/AtividadeDAO.class.php');
/**
 * Description of AtualAtivCTR
 *
 * @author anderson
 */
class AtualAtivCTR {

    //put your code here

    public function dados($info) {

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

        $dadosAtividade = array("dados" => $atividadeDAO->dados());
        $resAtividade = json_encode($dadosAtividade);

        return $resEquipAtiv . "_" . $resOSAtiv . "|" . $resAtividade;
        
    }

}
