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
class GrafProdDAO extends Conn {
    //put your code here

    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados() {

        $this->Conn = parent::getConn();

        $select = " select "
                . " dt_ref AS DATA "
                . " , qtd_ept AS QTDE_DIA "
                . " , round(avg(qtd_ept) over (partition by to_char(dt_ref, 'MM') order by dt_ref asc)) AS QTDE_MES "
                . " , round(avg(qtd_ept) over (partition by to_char(dt_ref, 'YYYY') order by dt_ref asc)) as QTDE_ANO "
                . " from "
                . " v_pmm_qtde_equip_plantio "
                . " where "
                . " id_frente = 73 ";

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $r = $this->Read->fetchAll();

        foreach ($r as $doc) {
            $dataEqp = $doc['DATA'];
            $qtdeDiaEqp = $doc['QTDE_DIA'];
            $qtdeMesEqp = $doc['QTDE_MES'];
            $qtdeAnoEqp = $doc['QTDE_ANO'];
        }


        $select = " select "
                . " vpd.dt_ref as DATA "
                . ", cf.frente_id as FRENTE_ID "
                . ", round(cf.capac / (ppc.dt_fim - ppc.dt_inic), 2) as PLANEJADO_DIA "
                . ", cf.capac as PLANEJADO_MES "
                . ", (cf.capac * 12) as PLANEJADO_ANO "
                . ", round(vpd.area, 2) as REALIZADO_DIA "
                . ", round(sum(vpd.area) over (partition by to_char(vpd.dt_ref, 'MM') order by vpd.dt_ref asc), 2) as REALIZADO_MES "
                . ", round(sum(vpd.area) over (partition by to_char(vpd.dt_ref, 'YYYY') order by vpd.dt_ref asc), 2) as REALIZADO_ANO "
                . " from "
                . " usinas.capac_frente cf "
                . ", usinas.part_per_cenar ppc "
                . ", usinas.vga_aval_plantio_diario vpd "
                . " where "
                . " vpd.frente_id = 73 "
                . " and cf.frente_id = vpd.frente_id "
                . " and vpd.dt_ref >= to_date('01/01/' || to_char(SYSDATE, 'YYYY'), 'DD/MM/YYYY') "
                . " and vpd.dt_ref between ppc.dt_inic and ppc.dt_fim "
                . " and cf.partpercen_id = ppc.partpercen_id "
                . " order by "
                . " vpd.dt_ref "
                . " asc ";

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $r1 = $this->Read->fetchAll();

        foreach ($r1 as $doc) {
            if ($dataEqp == $doc['DATA']) {
                $planDia = $doc['PLANEJADO_DIA'];
                $planMes = $doc['PLANEJADO_MES'];
                $planAno = $doc['PLANEJADO_ANO'];
                $realDia = $doc['REALIZADO_DIA'];
                $realMes = $doc['REALIZADO_MES'];
                $realAno = $doc['REALIZADO_ANO'];
            }
        }

        $dados = array(
            "idGrafProdFrente" => 1,
            "prodFrenteMetaDia" => $planDia,
            "prodFrenteRealDia" => $realDia,
            "prodFrenteMetaMes" => $planMes,
            "prodFrenteRealMes" => $realMes,
            "prodFrenteMetaAno" => $planAno,
            "prodFrenteRealAno" => $realAno,
            "mediaPlantMetaDia" => ($planDia / $qtdeDiaEqp),
            "mediaPlantRealDia" => ($realDia / $qtdeDiaEqp),
            "mediaPlantMetaMes" => ($planMes / $qtdeMesEqp),
            "mediaPlantRealMes" => ($realMes / $qtdeMesEqp),
            "mediaPlantMetaAno" => ($planAno / $qtdeAnoEqp),
            "mediaPlantRealAno" => ($realAno / $qtdeAnoEqp)
        );

        $infor1 = array("dados" => array($dados));
        $res1 = json_encode($infor1);

        return $res1;
    }

}
