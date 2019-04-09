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


        $select = " select "
                . " to_char(dt_qual, 'DD/MM/YYYY') as DATA "
                . " , round(calib_adub) as CALIB_ADUB_DIA "
                . " , round((SUM(calib_adub) OVER (PARTITION BY TO_CHAR(dt_qual, 'MM') ORDER BY dt_qual ASC)) "
                . " / (count(calib_adub) OVER (PARTITION BY TO_CHAR(dt_qual, 'MM') ORDER by dt_qual ASC))) CALIB_ADUB_MES "
                . " , round((SUM(calib_adub) OVER (PARTITION BY TO_CHAR(dt_qual, 'YYYY') ORDER BY dt_qual ASC)) "
                . " / (count(calib_adub) OVER (PARTITION BY TO_CHAR(dt_qual, 'YYYY') ORDER by dt_qual ASC))) CALIB_ADUB_ANO "
                . " , round(calib_inset) as CALIB_INSET_DIA "
                . " , round((SUM(calib_inset) OVER (PARTITION BY TO_CHAR(dt_qual, 'MM') ORDER BY dt_qual ASC)) "
                . " / (count(calib_inset) OVER (PARTITION BY TO_CHAR(dt_qual, 'MM') ORDER by dt_qual ASC))) CALIB_INSET_MES "
                . " , round((SUM(calib_inset) OVER (PARTITION BY TO_CHAR(dt_qual, 'YYYY') ORDER BY dt_qual ASC)) "
                . " / (count(calib_inset) OVER (PARTITION BY TO_CHAR(dt_qual, 'YYYY') ORDER by dt_qual ASC))) CALIB_INSET_ANO "
                . " , round(altura_cobric) as ALTURA_COBRIC_DIA "
                . " , round((SUM(altura_cobric) OVER (PARTITION BY TO_CHAR(dt_qual, 'MM') ORDER BY dt_qual ASC)) "
                . " / (count(altura_cobric) OVER (PARTITION BY TO_CHAR(dt_qual, 'MM') ORDER by dt_qual ASC))) ALTURA_COBRIC_MES "
                . " , round((SUM(altura_cobric) OVER (PARTITION BY TO_CHAR(dt_qual, 'YYYY') ORDER BY dt_qual ASC)) "
                . " / (count(altura_cobric) OVER (PARTITION BY TO_CHAR(dt_qual, 'YYYY') ORDER by dt_qual ASC))) ALTURA_COBRIC_ANO "
                . " , round(prof_sulc) as PROF_SULC_DIA "
                . " , round((SUM(prof_sulc) OVER (PARTITION BY TO_CHAR(dt_qual, 'MM') ORDER BY dt_qual ASC)) "
                . " / (count(prof_sulc) OVER (PARTITION BY TO_CHAR(dt_qual, 'MM') ORDER by dt_qual ASC))) PROF_SULC_MES "
                . " , round((SUM(prof_sulc) OVER (PARTITION BY TO_CHAR(dt_qual, 'YYYY') ORDER BY dt_qual ASC)) "
                . " / (count(prof_sulc) OVER (PARTITION BY TO_CHAR(dt_qual, 'YYYY') ORDER by dt_qual ASC))) PROF_SULC_ANO "
                . " , round(gemas) as GEMAS_DIA "
                . " , round((SUM(gemas) OVER (PARTITION BY TO_CHAR(dt_qual, 'MM') ORDER BY dt_qual ASC)) "
                . " / (count(gemas) OVER (PARTITION BY TO_CHAR(dt_qual, 'MM') ORDER by dt_qual ASC))) GEMAS_MES "
                . " , round((SUM(gemas) OVER (PARTITION BY TO_CHAR(dt_qual, 'YYYY') ORDER BY dt_qual ASC)) "
                . " / (count(gemas) OVER (PARTITION BY TO_CHAR(dt_qual, 'YYYY') ORDER by dt_qual ASC))) GEMAS_ANO "
                . " , round(falhas) as FALHAS_DIA "
                . " , round((SUM(falhas) OVER (PARTITION BY TO_CHAR(dt_qual, 'MM') ORDER BY dt_qual ASC)) "
                . " / (count(falhas) OVER (PARTITION BY TO_CHAR(dt_qual, 'MM') ORDER by dt_qual ASC))) FALHAS_MES "
                . " , round((SUM(falhas) OVER (PARTITION BY TO_CHAR(dt_qual, 'YYYY') ORDER BY dt_qual ASC)) "
                . " / (count(falhas) OVER (PARTITION BY TO_CHAR(dt_qual, 'YYYY') ORDER by dt_qual ASC))) FALHAS_ANO "
                . " from vga_qualidade "
                . " where "
                . " dt_qual >= to_date('01/01/' || to_char(sysdate, 'YYYY'), 'DD/MM/YYYY') ";

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $r4 = $this->Read->fetchAll();

        $dtGrafQual = "";
        $valorCalibAdubDia = 0;
        $valorCalibAdubMes = 0;
        $valorCalibAdubAno = 0;
        $valorCalibInsetDia = 0;
        $valorCalibInsetMes = 0;
        $valorCalibInsetAno = 0;
        $valorGemasDia = 0;
        $valorGemasMes = 0;
        $valorGemasAno = 0;
        $valorAltCobrDia = 0;
        $valorAltCobrMes = 0;
        $valorAltCobrAno = 0;
        $valorProfSulcDia = 0;
        $valorProfSulcMes = 0;
        $valorProfSulcAno = 0;
        $valorFalhasDia = 0;
        $valorFalhasMes = 0;
        $valorFalhasAno = 0;

        foreach ($r4 as $doc) {

            if(!empty($doc['CALIB_ADUB_DIA']) && !empty($doc['CALIB_INSET_DIA'])){
            
                $dtGrafQual = $doc['DATA'];
                $valorCalibAdubDia = $doc['CALIB_ADUB_DIA'];
                $valorCalibAdubMes = $doc['CALIB_ADUB_MES'];
                $valorCalibAdubAno = $doc['CALIB_ADUB_ANO'];
                $valorCalibInsetDia = $doc['CALIB_INSET_DIA'];
                $valorCalibInsetMes = $doc['CALIB_INSET_MES'];
                $valorCalibInsetAno = $doc['CALIB_INSET_ANO'];
                $valorGemasDia = $doc['GEMAS_DIA'];
                $valorGemasMes = $doc['GEMAS_MES'];
                $valorGemasAno = $doc['GEMAS_ANO'];
                $valorAltCobrDia = $doc['ALTURA_COBRIC_DIA'];
                $valorAltCobrMes = $doc['ALTURA_COBRIC_MES'];
                $valorAltCobrAno = $doc['ALTURA_COBRIC_ANO'];
                $valorProfSulcDia = $doc['PROF_SULC_DIA'];
                $valorProfSulcMes = $doc['PROF_SULC_MES'];
                $valorProfSulcAno = $doc['PROF_SULC_ANO'];
                $valorFalhasDia = $doc['FALHAS_DIA'];
                $valorFalhasMes = $doc['FALHAS_MES'];
                $valorFalhasAno = $doc['FALHAS_ANO'];
                
            }

        }

        $dados = array(
            "idGrafQual" => 1,
            "dtGrafQual" => $dtGrafQual,
            "valorCalibAdubDia" => $valorCalibAdubDia,
            "valorCalibAdubMes" => $valorCalibAdubMes,
            "valorCalibAdubAno" => $valorCalibAdubAno,
            "valorCalibInsetDia" => $valorCalibInsetDia,
            "valorCalibInsetMes" => $valorCalibInsetMes,
            "valorCalibInsetAno" => $valorCalibInsetAno,
            "valorGemasDia" => $valorGemasDia,
            "valorGemasMes" => $valorGemasMes,
            "valorGemasAno" => $valorGemasAno,
            "valorAltCobrDia" => $valorAltCobrDia,
            "valorAltCobrMes" => $valorAltCobrMes,
            "valorAltCobrAno" => $valorAltCobrAno,
            "valorProfSulcDia" => $valorProfSulcDia,
            "valorProfSulcMes" => $valorProfSulcMes,
            "valorProfSulcAno" => $valorProfSulcAno,
            "valorFalhasDia" => $valorFalhasDia,
            "valorFalhasMes" => $valorFalhasMes,
            "valorFalhasAno" => $valorFalhasAno
        );

        $infor4 = array("dados" => array($dados));
        $res4 = json_encode($infor4);


        return $res1 . "#" . $res2 . "|" . $res3 . "?" . $res4;
    }

}
