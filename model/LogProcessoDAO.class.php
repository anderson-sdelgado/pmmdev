<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */
require_once('../dbutil/Conn.class.php');
/**
 * Description of LogProcessoDAO
 *
 * @author anderson
 */
class LogProcessoDAO extends Conn {
    //put your code here
    
    public function insLogErro($d) {

        $this->Conn = parent::getConn();
        
        $sql = "INSERT INTO PMM_LOG_PROCESSO ("
                . " ACTIVITY "
                . " , DADOS "
                . " , DTHR "
                . " ) "
                . " VALUES ("
                . " SYSDATE "
                . " , ?"
                . " , ?"
                . " , TO_DATE('". $d->dthr ."', 'DD/MM/YYYY HH24:MI'))";

        $this->Create = $this->Conn->prepare($sql);
        $this->Create->bindParam(1, $d->activity, PDO::PARAM_STR, 30);
        $this->Create->bindParam(2, $d->processo, PDO::PARAM_STR, 32000);
        $this->Create->execute();

    }
    
}
