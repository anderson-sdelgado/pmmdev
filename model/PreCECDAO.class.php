<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
/**
 * Description of InsMotoMecDAO
 *
 * @author anderson
 */
class PreCECDAO extends Conn {
    //put your code here

    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function verifPreCEC($precec, $base) {

        $select = " SELECT "
                        . " COUNT(*) AS QTDE "
                    . " FROM "
                        . " ECM_PRE_CEC_CANA "
                    . " WHERE "
                        . " DATA_HORA_SAIDA_CAMPO_CEL = TO_DATE('" . $precec->dataSaidaCampo . "','DD/MM/YYYY HH24:MI')"
                        . " AND "
                        . " EQUIP = " . $precec->cam . " ";

        $this->Conn = parent::getConn($base);
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $v = $item['QTDE'];
        }

        return $v;
    }
    
    public function insPreCEC($precec, $base) {

        $insert = " INSERT INTO "
                    . " ECM_PRE_CEC_CANA "
                    . " ( ID, EQUIP, LIB_EQUIP, COLHED_EQUIP, OPER_COLHED_EQUIP, COLABORADOR "
                    . " , CARRETA_1, LIB_CARRETA_1, COLHED_CARRETA_1, OPER_COLHED_CARRETA_1 "
                    . " , CARRETA_2, LIB_CARRETA_2, COLHED_CARRETA_2, OPER_COLHED_CARRETA_2 "
                    . " , CARRETA_3, LIB_CARRETA_3, COLHED_CARRETA_3, OPER_COLHED_CARRETA_3 "
                    . " , DATA_HORA_CHEGADA_CAMPO, DATA_HORA_CHEGADA_CAMPO_CEL, DATA_HORA_SAIDA_CAMPO "
                    . " , DATA_HORA_SAIDA_CAMPO_CEL, DATA_HORA_SAIDA_USINA, DATA_HORA_SAIDA_USINA_CEL "
                    . " , DATA_HORA_TRANS "
                    . " , NOTEIRO, TURNO "
                    . " ) "
                    . " VALUES ( "
                    . " ECM_PRE_CEC_CANA_SEQ.NEXTVAL "
                    . " , " . $precec->cam
                    . " , " . $this->verifValor($precec->libCam)
                    . " , null "
                    . " , null "
                    . " , " . $precec->moto
                    . " , " . $this->verifValor($precec->carr1)
                    . " , " . $this->verifValor($precec->libCarr1)
                    . " , null "
                    . " , null "
                    . " , " . $this->verifValor($precec->carr2)
                    . " , " . $this->verifValor($precec->libCarr2)
                    . " , null "
                    . " , null "
                    . " , " . $this->verifValor($precec->carr3)
                    . " , " . $this->verifValor($precec->libCarr3)
                    . " , null "
                    . " , null "
                    . " , TO_DATE('" . $precec->dataChegCampo . "','DD/MM/YYYY HH24:MI')"
                    . " , TO_DATE('" . $precec->dataChegCampo . "','DD/MM/YYYY HH24:MI')"
                    . " , TO_DATE('" . $precec->dataSaidaCampo . "','DD/MM/YYYY HH24:MI')"
                    . " , TO_DATE('" . $precec->dataSaidaCampo . "','DD/MM/YYYY HH24:MI')"
                    . " , TO_DATE('" . $precec->dataSaidaUsina . "','DD/MM/YYYY HH24:MI')"
                    . " , TO_DATE('" . $precec->dataSaidaUsina . "','DD/MM/YYYY HH24:MI')"
                    . " , SYSDATE "
                    . " , " . $precec->moto
                    . " , " . $this->verifValor($precec->turno)
                    . " ) ";

        $this->Conn = parent::getConn($base);
        $this->Create = $this->Conn->prepare($insert);
        $this->Create->execute();
        
    }

    private function verifValor($inf) {
        if ($inf == 0) {
            return 'null';
        } else {
            return $inf;
        }
    }

}
