<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
/**
 * Description of ComponenteDAO
 *
 * @author anderson
 */
class ComponenteDAO extends Conn {

    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados() {

        $select = " SELECT "
                . " COMPONENTE_ID AS \"idComponente\" "
                . " , CD AS \"codComponente\" "
                . " , DESCR AS \"descrComponente\" "
                . " FROM "
                . " VMB_COMPONENTE_AUTO "
                . " ORDER BY "
                . " CD "
                . " ASC ";

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }

}
