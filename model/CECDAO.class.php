<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once ('../dbutil/Conn.class.php');
/**
 * Description of CECDAO
 *
 * @author anderson
 */
class CECDAO extends Conn {

    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function pesqCEC($idEquip) {

        $result = null;

        while (empty($result)) {

            $select = " SELECT "
                        . " UV.CAMINHAO AS \"caminhaoCEC\" "
                        . " , UV.CD_FRENTE AS \"codFrenteCEC\" "
                        . " , UV.CEC_PAI AS \"cecPaiCEC\" "
                        . " , TO_CHAR(UV.DT_HR_ENTRADA, 'DD/MM/YYYY HH24:MI') AS \"dthrEntradaCEC\" "
                        . " , UV.POSSUI_SORTEIO AS \"possuiSorteioCEC\" "
                        . " , NVL(UV.CEC_SORTEADO_1, 0) AS \"cecSorteado1CEC\" "
                        . " , NVL(UV.UNID_SORTEADA_1, 0) AS \"unidadeSorteada1CEC\" "
                        . " , NVL(UV.CEC_SORTEADO_2, 0) AS \"cecSorteado2CEC\" "
                        . " , NVL(UV.UNID_SORTEADA_2, 0) AS \"unidadeSorteada2CEC\" "
                        . " , NVL(UV.CEC_SORTEADO_3, 0) AS \"cecSorteado3CEC\" "
                        . " , NVL(UV.UNID_SORTEADA_3, 0) AS \"unidadeSorteada3CEC\" "
                        . " , NVL(REPLACE(UV.PESO_LIQUIDO, ',', '.'), 0) AS \"pesoLiquidoCEC\" "
                    . " FROM "
                        . " INTEGRACAO.ULTIMAVIAGEM UV "
                        . " , EQUIP E "
                    . " WHERE " 
                        . " E.EQUIP_ID = " . $idEquip
                        . " AND "
                        . " UV.CAMINHAO = E.NRO_EQUIP "
                    . " ORDER BY "
                        . " UV.ULTVIAGEM_ID  "
                    . " DESC ";

            $this->Conn = parent::getConn();
            $this->Read = $this->Conn->prepare($select);
            $this->Read->setFetchMode(PDO::FETCH_ASSOC);
            $this->Read->execute();
            $result = $this->Read->fetchAll();
            
        }

        return $result;
    }

    public function deleteCEC($cam) {

        $sql = " call pk_integra_balanca.pkb_apaga_ultviagem(?)";
        $this->Conn = parent::getConn();
        $stmt = $this->Conn->prepare($sql);
        $stmt->bindParam(1, $cam, PDO::PARAM_INT, 32);

        $stmt->execute();
    }

}
