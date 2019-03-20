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
                . " , round(sum(vpd.area) over (partition by to_char(vpd.dt_ref, 'MM') order by vpd.dt_ref asc), 2) as REALIZADO_MES "
                . " , round(sum(vpd.area) over (partition by to_char(vpd.dt_ref, 'YYYY') order by vpd.dt_ref asc), 2) as REALIZADO_ANO "
                . " from "
                . " usinas.capac_frente cf "
                . " , usinas.part_per_cenar ppc "
                . " , usinas.vga_aval_plantio_diario vpd "
                . " where "
                . " vpd.frente_id = 73 "
                . " and cf.frente_id = vpd.frente_id "
                . " and vpd.dt_ref >= to_date('01/01/' || to_char(SYSDATE, 'YYYY'), 'DD/MM/YYYY') "
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

        $dados = array(
            "idGrafProdFrente" => 1,
            "prodFrenteMetaDia" => $planDia,
            "prodFrenteRealDia" => $realDia,
            "prodFrenteMetaMes" => $planMes,
            "prodFrenteRealMes" => $realMes,
            "prodFrenteMetaAno" => $planAno,
            "prodFrenteRealAno" => $realAno,
            "mediaPlantMetaDia" => 0,
            "mediaPlantRealDia" => 0,
            "mediaPlantMetaMes" => 0,
            "mediaPlantRealMes" => 0,
            "mediaPlantMetaAno" => 0,
            "mediaPlantRealAno" => 0
        );

        $infor1 = array("dados" => array($dados));
        $res1 = json_encode($infor1);

        $select = " select "
                . " to_char(vpd.dt_ref, 'MM') AS \"mesPlanReal\" "
                . " , cf.capac AS \"valorMesPlan\" "
                . " , sum(cf.capac) over (order by to_char(vpd.dt_ref, 'MM') asc) AS \"valorAcumPlan\" "
                . " , round(sum(vpd.area), 2) AS \"valorMesReal\" "
                . " , round(sum(sum(vpd.area)) over (order by to_char(vpd.dt_ref, 'MM') asc), 2) AS \"valorAcumReal\" "
                . " from "
                . " capac_frente cf "
                . " , part_per_cenar ppc "
                . " , vga_aval_plantio_diario vpd "
                . " where "
                . " vpd.frente_id = 73 "
                . " and cf.frente_id = vpd.frente_id "
                . " and vpd.dt_ref >= to_date('01/01/' || to_char(SYSDATE, 'YYYY'), 'DD/MM/YYYY') "
                . " and vpd.dt_ref between ppc.dt_inic and ppc.dt_fim "
                . " and cf.partpercen_id = ppc.partpercen_id "
                . " group by "
                . " to_char(vpd.dt_ref, 'MM') "
                . " , cf.capac ";

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $r2 = $this->Read->fetchAll();

        $infor2 = array("dados" => $r2);
        $res2 = json_encode($infor2);

        $select = " select "
                . " round((hr_aponta/hr_planej) * 100) AS DISP_OPER_DIA "
                . " , round((hr_para_manut/hr_aponta) * 100) as DISP_CAMPO_DIA "
                . " , round(((SUM(hr_aponta) OVER (PARTITION BY TO_CHAR(DT, 'MM') ORDER BY DT ASC)) "
                . " / (SUM(hr_planej) OVER (PARTITION BY TO_CHAR(DT, 'MM') ORDER BY DT ASC))) * 100) as DISP_OPER_MES "
                . " , round(((SUM(hr_para_manut) OVER (PARTITION BY TO_CHAR(DT, 'MM') ORDER BY DT ASC)) "
                . " / (SUM(hr_aponta) OVER (PARTITION BY TO_CHAR(DT, 'MM') ORDER BY DT ASC))) * 100) as DISP_CAMPO_MES "
                . " , round(((SUM(hr_aponta) OVER (PARTITION BY TO_CHAR(DT, 'YYYY') ORDER BY DT ASC)) "
                . " / (SUM(hr_planej) OVER (PARTITION BY TO_CHAR(DT, 'YYYY') ORDER BY DT ASC))) * 100) as DISP_OPER_ANO "
                . " , round(((SUM(hr_para_manut) OVER (PARTITION BY TO_CHAR(DT, 'YYY') ORDER BY DT ASC)) "
                . " / (SUM(hr_aponta) OVER (PARTITION BY TO_CHAR(DT, 'YYYY') ORDER BY DT ASC))) * 100) as DISP_CAMPO_ANO "
                . " from "
                . " pmm_graf_disp_campo "
                . " where frente_id = 73 ";

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $r3 = $this->Read->fetchAll();

        foreach ($r3 as $doc) {
            $valorOperTratorPlanDia = $doc['DISP_OPER_DIA'];
            $valorCampoTratorPlanDia = $doc['DISP_CAMPO_DIA'];
            $valorOperTratorPlanMes = $doc['DISP_OPER_MES'];
            $valorCampoTratorPlanMes = $doc['DISP_CAMPO_MES'];
            $valorOperTratorPlanAno = $doc['DISP_OPER_ANO'];
            $valorCampoTratorPlanAno = $doc['DISP_CAMPO_ANO'];
        }

        $dados = array(
            "idGrafDispEquip" => 1,
            "valorOperTratorPlanDia" => $valorOperTratorPlanDia,
            "valorCampoTratorPlanDia" => $valorCampoTratorPlanDia,
            "valorOperTratorPlanMes" => $valorOperTratorPlanMes,
            "valorCampoTratorPlanMes" => $valorCampoTratorPlanMes,
            "valorOperTratorPlanAno" => $valorOperTratorPlanAno,
            "valorCampoTratorPlanAno" => $valorCampoTratorPlanAno,
            "valorOperCamMudaDia" => 0,
            "valorCampoCamMudaDia" => 0,
            "valorOperCamMudaMes" => 0,
            "valorCampoCamMudaMes" => 0,
            "valorOperCamMudaAno" => 0,
            "valorCampoCamMudaAno" => 0,
            "valorOperColhedoraDia" => 0,
            "valorCampoColhedoraDia" => 0,
            "valorOperColhedoraMes" => 0,
            "valorCampoColhedoraMes" => 0,
            "valorOperColhedoraAno" => 0,
            "valorCampoColhedoraAno" => 0,
            "valorOperTratorTransbDia" => 0,
            "valorCampoTratorTransbDia" => 0,
            "valorOperTratorTransbMes" => 0,
            "valorCampoTratorTransbMes" => 0,
            "valorOperTratorTransbAno" => 0,
            "valorCampoTratorTransbAno" => 0
        );

        $infor3 = array("dados" => array($dados));
        $res3 = json_encode($infor3);

        return $res1 . "#" . $res2 . "|" . $res3;
    }

}
