<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'Conn.class.php';
/**
 * Description of VerifPneuDAO
 *
 * @author anderson
 */
class VerifPneuDAO extends Conn {
    //put your code here
    
    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados($valor) {

        $this->Conn = parent::getConn();
        
        $select = " SELECT "
                . " EQUIPCOMPO_ID AS \"idPneu\" "
                . " , CD AS \"codPneu\" "
                . " FROM "
                . " VMB_PNEU "
                . " WHERE "
                . " CD LIKE '" . $valor . "'";

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $r1 = $this->Read->fetchAll();

        $dados = array("dados"=>$r1);
        $res1 = json_encode($dados);
        
        return $res1;
        
    }
    
}
