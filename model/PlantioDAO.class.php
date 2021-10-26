<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
/**
 * Description of PlantioDAO
 *
 * @author anderson
 */
class PlantioDAO extends Conn {
    //put your code here
    
    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados($matric, $base) {
        
        $select = " SELECT "
                        . " TO_CHAR(VPP.DT_REF, 'DD/MM/YYYY') AS \"dthrPlantio\" "
                        . " , VPP.QTDE_PLANEJ AS \"qtdeProdPlanej\" "
                        . " , VPP.QTDE_REAL AS \"qtdeProdReal\" "
                        . " , CASE "
                        . " WHEN VQP.QTD_EQUIP < 1 THEN 0 "
                        . " ELSE ROUND((VPP.QTDE_PLANEJ/VQP.QTD_EQUIP), 2) END AS \"mediaProdPlanej\" "
                        . " , CASE "
                        . " WHEN VQP.QTD_EQUIP < 1 THEN 0 "
                        . " ELSE ROUND((VPP.QTDE_REAL/VQP.QTD_EQUIP), 2) END AS \"mediaProdReal\" "
                        . " , ROUND((VRF.HR_PARA_MANUT * 100) / VRF.HR_APONTA) AS \"porcDispon\" "
                    . " FROM "
                        . " VIEW_PROD_PLANTIO VPP "
                        . " , VIEW_QTDE_PLANT_FRENTE VQP "
                        . " , USINAS.VGA_REAL_FRENTE VRF "
                        . " , VMB_FUNC_FRENTE FF "
                        . " , FUNC F "
                    . " WHERE "
                        . " F.CD = " . $matric
                        . " AND "
                        . " CD_ATIV IN (179, 420) "
                        . " AND "
                        . " VPP.DT_REF = (SELECT "
                        . "                  MAX(DT_REF) "
                        . "              FROM "
                        . "                  VIEW_QTDE_PLANT_FRENTE) "
                        . " AND "
                        . " VPP.FRENTE_ID = FF.FRENTE_ID "
                        . " AND "
                        . " FF.FUNC_ID = F.FUNC_ID "
                        . " AND "
                        . " VPP.DT_REF = VQP.DT_REF "
                        . " AND "
                        . " VPP.DT_REF = VRF.DT "
                        . " AND "
                        . " VPP.FRENTE_ID = VQP.FRENTE_ID "
                        . " AND "
                        . " VPP.FRENTE_ID = VRF.FRENTE_ID "
                        . " FETCH FIRST 1 ROWS ONLY ";

        $this->Conn = parent::getConn($base);
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
        
    }
    
}
