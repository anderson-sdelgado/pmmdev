<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'Conn.class.php';

/**
 * Description of GrafPlantioDAO
 *
 * @author anderson
 */
class GrafPlantioDAO extends Conn {
    //put your code here

    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados() {

        $this->Conn = parent::getConn();

        $select = " select "
                . " vpd.dt_ref as DATA "
                . " , cf.frente_id as FRENTE_ID "
                . " , round(cf.capac / (ppc.dt_fim - ppc.dt_inic), 2) as PLANEJADO_DIA "
                . " , cf.capac as PLANEJADO_MES "
                . " , (cf.capac * 12) as PLANEJADO_ANO "
                . " , round(vpd.area, 2) as REALIZADO_DIA "
                . " , round(sum(vpd.area) OVER (PARTITION BY TO_CHAR(vpd.dt_ref, 'MM') ORDER BY vpd.dt_ref asc), 2) as REALIZADO_MES "
                . " , round(sum(vpd.area) OVER (PARTITION BY TO_CHAR(vpd.dt_ref, 'YYYY') ORDER BY vpd.dt_ref asc), 2) as REALIZADO_ANO "
                . " from "
                . " usinas.capac_frente cf "
                . " , usinas.part_per_cenar ppc "
                . " , usinas.vga_aval_plantio_diario vpd "
                . " where "
                . " vpd.frente_id = 73 "
                . " and cf.frente_id = vpd.frente_id "
                . " and vpd.dt_ref >= TO_DATE('01/01/' || TO_CHAR(SYSDATE, 'YYYY'), 'DD/MM/YYYY') "
                . " and vpd.dt_ref between ppc.dt_inic and ppc.dt_fim "
                . " and cf.partpercen_id = ppc.partpercen_id "
                . " order by  "
                . " vpd.dt_ref "
                . " asc ";

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $r1 = $this->Read->fetchAll();

        foreach ($r1 as $doc) {
            $planDia = $doc['PLANEJADO_DIA'];
            $planMes = $doc['PLANEJADO_MES'];
            $planAno = $doc['PLANEJADO_ANO'];
            $realDia = $doc['REALIZADO_DIA'];
            $realMes = $doc['REALIZADO_MES'];
            $realAno = $doc['REALIZADO_ANO'];
        }

        $dados = array("prodFrenteMetaDia" => $planDia,
        "prodFrenteRealDia" => $realDia,
        "prodFrenteMetaMes" => $planMes,
        "prodFrenteRealMes" => $realMes,
        "prodFrenteMetaAno" => $planAno,
        "prodFrenteRealAno" => $realAno,
        "mediaPlantMetaDia" => $planDia,
        "mediaPlantRealDia" => $realDia,
        "mediaPlantMetaMes" => $planMes,
        "mediaPlantRealMes" => $realMes,
        "mediaPlantMetaAno" => $planAno,
        "mediaPlantRealAno" => $realAno
        );

        return $dados;
    }

}
