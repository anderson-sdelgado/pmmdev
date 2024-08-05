<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */
require_once('../dbutil/ConnApex.class.php');
/**
 * Description of CarregCanaDAO
 *
 * @author anderson
 */
class CarregCanaDAO extends ConnApex {
    
    public function verLocalCarreg($nroEquip) {

        $select = " SELECT "
                    . " COUNT(MSG.CDGFRENTE) AS QTDE "
                . " FROM "
                    . " LOGTRAC.SGA_MSG_PAINEL_FRENTE MSG "
                    . " , LOGTRAC.TRA_ALOCACAO AL "
                    . " , PROPR_AGR PGR "
                . " WHERE "
                    . " AL.CDGEQUIPAMENTO = " . $nroEquip
                    . " AND "
                    . " MSG.CDGFA = AL.CDGFA "
                    . " AND "
                    . " PGR.CD = AL.CDGFA "
                    . " AND " 
                    . " AL.DTAENCERRAMENTO IS NULL "
                    . " AND "
                    . " AL.DTAALOCACAO >= TRUNC(SYSDATE)-1 ";
        
        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
                $v = $item['QTDE'];
        }

        return $v;
        
    }
    
    public function retLocalCarreg($nroEquip) {

        $select = " SELECT "
                    . " MSG.CDGFRENTE AS \"codFrente\" "
                    . " , TRIM(MSG.CDGFA) AS \"codPropriedade\" "
                    . " , PGR.DESCR AS \"descrPropriedade\" "
                    . " , CARACTER(MSG.DSCMENSAGEM) AS \"descrCaminho\" "
                . " FROM "
                    . " LOGTRAC.SGA_MSG_PAINEL_FRENTE MSG "
                    . " , LOGTRAC.TRA_ALOCACAO AL "
                    . " , PROPR_AGR PGR "
                . " WHERE "
                    . " AL.CDGEQUIPAMENTO = " . $nroEquip
                    . " AND "
                    . " MSG.CDGFA = AL.CDGFA "
                    . " AND "
                    . " PGR.CD = AL.CDGFA "
                    . " AND " 
                    . " AL.DTAENCERRAMENTO IS NULL "
                    . " AND "
                    . " AL.DTAALOCACAO >= TRUNC(SYSDATE)-1 ";

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
        
    }
}
