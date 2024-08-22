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
                    . " TRIM(MSG.CDGFA(+)) = TRIM(AL.CDGFA) "
                    . " AND "
                    . " PGR.CD = AL.CDGFA "
                    . " AND "
                    . " AL.CDGFRENTE = MSG.CDGFRENTE(+) " 
                    . " AND " 
                    . " AL.DTAENCERRAMENTO IS NULL ";
        
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
                    . " AL.CDGFRENTE AS \"codFrente\" "
                    . " , NVL(MSG.ORDEM_SRV,'NÃO CADASTRADO ') AS \"descrOS\" "
                    . " , NVL(MSG.DS_LIBERACAO,'NÃO CADASTRADO ') AS \"descrLiberacao\" "
                    . " , TRIM(AL.CDGFA) AS \"codPropriedade\" "
                    . " , PGR.DESCR AS \"descrPropriedade\" "
                    . " , NVL(CARACTER(MSG.DSCMENSAGEM),'NÃO CADASTRADO, VERIFICAR COM COA') AS \"descrCaminho\" "
                . " FROM "
                    . " LOGTRAC.SGA_MSG_PAINEL_FRENTE MSG "
                    . " , LOGTRAC.TRA_ALOCACAO AL "
                    . " , PROPR_AGR PGR "
                . " WHERE "
                    . " AL.CDGEQUIPAMENTO = " . $nroEquip
                    . " AND "
                    . " TRIM(MSG.CDGFA(+)) = TRIM(AL.CDGFA) "
                    . " AND "
                    . " PGR.CD = AL.CDGFA "
                    . " AND "
                    . " AL.CDGFRENTE = MSG.CDGFRENTE(+) " 
                    . " AND " 
                    . " AL.DTAENCERRAMENTO IS NULL ";

        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
        
    }
}
