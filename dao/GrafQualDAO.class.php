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
class GrafQualDAO extends Conn {
    //put your code here

    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados() {

        $this->Conn = parent::getConn();

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

            if (!empty($doc['CALIB_ADUB_DIA']) && !empty($doc['CALIB_INSET_DIA'])) {

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

        return $res4;
    }

}
