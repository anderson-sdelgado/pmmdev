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
class GrafDispCampoDAO extends Conn {
    //put your code here

    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados() {

        $this->Conn = parent::getConn();

        $select = " select "
                . " rf.dt as DATA "
                . " , round(rf.hr_aponta) as QTDEHRAPONTDIA "
                . " , round(sum(rf.hr_aponta) over (partition by to_char(rf.dt, 'MM') order by rf.dt asc)) as QTDEHRAPONTMES "
                . " , round(sum(rf.hr_aponta) over (partition by to_char(rf.dt, 'YYYY') order by rf.dt asc)) as QTDEHRAPONTANO "
                . " , round(rf.hr_para_manut) as QTDEHRPARADADIA "
                . " , round(sum(rf.hr_para_manut) over (partition by to_char(rf.dt, 'MM') order by rf.dt asc)) as QTDEHRPARADAMES "
                . " , round(sum(rf.hr_para_manut) over (partition by to_char(rf.dt, 'YYYY') order by rf.dt asc)) as QTDEHRPARADAANO "
                . " from "
                . " vga_real_frente rf "
                . " where "
                . " frente_id = 73 "
                . " and "
                . " ativagr_id = 537 ";

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $rTPCA = $this->Read->fetchAll();

        foreach ($rTPCA as $doc) {
            $dataTPDO = $doc['DATA'];
            $qtdeHrApontDiaTPCA = $doc['QTDEHRAPONTDIA'];
            $qtdeHrApontMesTPCA = $doc['QTDEHRAPONTMES'];
            $qtdeHrApontAnoTPCA = $doc['QTDEHRAPONTANO'];
            $qtdeHrParadaDiaTPCA = $doc['QTDEHRPARADADIA'];
            $qtdeHrParadaMesTPCA = $doc['QTDEHRPARADAMES'];
            $qtdeHrParadaAnoTPCA = $doc['QTDEHRPARADAANO'];
        }

        $select = " select distinct "
                . " fp.dt as DATA "
                . " , round(fp.qtde) as QTDEDIA "
                . " , round(sum(fp.qtde) over (partition by to_char(fp.dt, 'MM') order by fp.dt asc)) as QTDEMES "
                . " , round(sum(fp.qtde) over (partition by to_char(fp.dt, 'YYYY') order by fp.dt asc)) as QTDEANO "
                . " from "
                . " vga_frente_plan fp "
                . " where "
                . " fp.frente_id = 73 "
                . " and "
                . " fp.ativagr_id = 537 "
                . " and "
                . " fp.dt <= sysdate ";

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $rTPDO = $this->Read->fetchAll();

        foreach ($rTPDO as $doc) {
            if ($dataTPDO == $doc['DATA']) {
                $qtdeDiaTPDO = $doc['QTDEDIA'];
                $qtdeMesTPDO = $doc['QTDEMES'];
                $qtdeAnoTPDO = $doc['QTDEANO'];
            }
        }

////////////////////////////////

        $select = " select "
                . " rf.dt as DATA "
                . " , round(rf.hr_aponta) as QTDEHRAPONTDIA "
                . " , round(sum(rf.hr_aponta) over (partition by to_char(rf.dt, 'MM') order by rf.dt asc)) as QTDEHRAPONTMES "
                . " , round(sum(rf.hr_aponta) over (partition by to_char(rf.dt, 'YYYY') order by rf.dt asc)) as QTDEHRAPONTANO "
                . " , round(rf.hr_para_manut) as QTDEHRPARADADIA "
                . " , round(sum(rf.hr_para_manut) over (partition by to_char(rf.dt, 'MM') order by rf.dt asc)) as QTDEHRPARADAMES "
                . " , round(sum(rf.hr_para_manut) over (partition by to_char(rf.dt, 'YYYY') order by rf.dt asc)) as QTDEHRPARADAANO "
                . " from "
                . " vga_real_frente rf "
                . " where "
                . " frente_id = 73 "
                . " and "
                . " ativagr_id = 543 ";

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $rCTCA = $this->Read->fetchAll();

        foreach ($rCTCA as $doc) {
            $dataCTDO = $doc['DATA'];
            $qtdeHrApontDiaCTCA = $doc['QTDEHRAPONTDIA'];
            $qtdeHrApontMesCTCA = $doc['QTDEHRAPONTMES'];
            $qtdeHrApontAnoCTCA = $doc['QTDEHRAPONTANO'];
            $qtdeHrParadaDiaCTCA = $doc['QTDEHRPARADADIA'];
            $qtdeHrParadaMesCTCA = $doc['QTDEHRPARADAMES'];
            $qtdeHrParadaAnoCTCA = $doc['QTDEHRPARADAANO'];
        }

        $select = " select distinct "
                . " fp.dt as DATA "
                . " , round(fp.qtde) as QTDEDIA "
                . " , round(sum(fp.qtde) over (partition by to_char(fp.dt, 'MM') order by fp.dt asc)) as QTDEMES "
                . " , round(sum(fp.qtde) over (partition by to_char(fp.dt, 'YYYY') order by fp.dt asc)) as QTDEANO "
                . " from "
                . " vga_frente_plan fp "
                . " where "
                . " fp.frente_id = 73 "
                . " and "
                . " fp.ativagr_id = 543 "
                . " and "
                . " fp.dt <= sysdate ";

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $rCTDO = $this->Read->fetchAll();

        foreach ($rCTDO as $doc) {
            if ($dataCTDO == $doc['DATA']) {
                $qtdeDiaCTDO = $doc['QTDEDIA'];
                $qtdeMesCTDO = $doc['QTDEMES'];
                $qtdeAnoCTDO = $doc['QTDEANO'];
            }
        }

////////////////////////////////

        $select = " select "
                . " rf.dt as DATA "
                . " , round(rf.hr_aponta) as QTDEHRAPONTDIA "
                . " , round(sum(rf.hr_aponta) over (partition by to_char(rf.dt, 'MM') order by rf.dt asc)) as QTDEHRAPONTMES "
                . " , round(sum(rf.hr_aponta) over (partition by to_char(rf.dt, 'YYYY') order by rf.dt asc)) as QTDEHRAPONTANO "
                . " , round(rf.hr_para_manut) as QTDEHRPARADADIA "
                . " , round(sum(rf.hr_para_manut) over (partition by to_char(rf.dt, 'MM') order by rf.dt asc)) as QTDEHRPARADAMES "
                . " , round(sum(rf.hr_para_manut) over (partition by to_char(rf.dt, 'YYYY') order by rf.dt asc)) as QTDEHRPARADAANO "
                . " from "
                . " vga_real_frente rf "
                . " where "
                . " frente_id = 73 "
                . " and "
                . " ativagr_id = 553 ";

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $rCOCA = $this->Read->fetchAll();

        foreach ($rCOCA as $doc) {
            $dataCODO = $doc['DATA'];
            $qtdeHrApontDiaCOCA = $doc['QTDEHRAPONTDIA'];
            $qtdeHrApontMesCOCA = $doc['QTDEHRAPONTMES'];
            $qtdeHrApontAnoCOCA = $doc['QTDEHRAPONTANO'];
            $qtdeHrParadaDiaCOCA = $doc['QTDEHRPARADADIA'];
            $qtdeHrParadaMesCOCA = $doc['QTDEHRPARADAMES'];
            $qtdeHrParadaAnoCOCA = $doc['QTDEHRPARADAANO'];
        }

        $select = " select distinct "
                . " fp.dt as DATA "
                . " , round(fp.qtde) as QTDEDIA "
                . " , round(sum(fp.qtde) over (partition by to_char(fp.dt, 'MM') order by fp.dt asc)) as QTDEMES "
                . " , round(sum(fp.qtde) over (partition by to_char(fp.dt, 'YYYY') order by fp.dt asc)) as QTDEANO "
                . " from "
                . " vga_frente_plan fp "
                . " where "
                . " fp.frente_id = 73 "
                . " and "
                . " fp.ativagr_id = 553 "
                . " and "
                . " fp.dt <= sysdate ";

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $rCODO = $this->Read->fetchAll();

        foreach ($rCODO as $doc) {
            if ($dataCODO == $doc['DATA']) {
                $qtdeDiaCODO = $doc['QTDEDIA'];
                $qtdeMesCODO = $doc['QTDEMES'];
                $qtdeAnoCODO = $doc['QTDEANO'];
            }
        }

////////////////////////////////

        $select = " select "
                . " rf.dt as DATA "
                . " , round(rf.hr_aponta) as QTDEHRAPONTDIA "
                . " , round(sum(rf.hr_aponta) over (partition by to_char(rf.dt, 'MM') order by rf.dt asc)) as QTDEHRAPONTMES "
                . " , round(sum(rf.hr_aponta) over (partition by to_char(rf.dt, 'YYYY') order by rf.dt asc)) as QTDEHRAPONTANO "
                . " , round(rf.hr_para_manut) as QTDEHRPARADADIA "
                . " , round(sum(rf.hr_para_manut) over (partition by to_char(rf.dt, 'MM') order by rf.dt asc)) as QTDEHRPARADAMES "
                . " , round(sum(rf.hr_para_manut) over (partition by to_char(rf.dt, 'YYYY') order by rf.dt asc)) as QTDEHRPARADAANO "
                . " from "
                . " vga_real_frente rf "
                . " where "
                . " frente_id = 73 "
                . " and "
                . " ativagr_id = 554 ";

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $rTTCA = $this->Read->fetchAll();

        foreach ($rTTCA as $doc) {
            $dataTTDO = $doc['DATA'];
            $qtdeHrApontDiaTTCA = $doc['QTDEHRAPONTDIA'];
            $qtdeHrApontMesTTCA = $doc['QTDEHRAPONTMES'];
            $qtdeHrApontAnoTTCA = $doc['QTDEHRAPONTANO'];
            $qtdeHrParadaDiaTTCA = $doc['QTDEHRPARADADIA'];
            $qtdeHrParadaMesTTCA = $doc['QTDEHRPARADAMES'];
            $qtdeHrParadaAnoTTCA = $doc['QTDEHRPARADAANO'];
        }

        $select = " select distinct "
                . " fp.dt as DATA "
                . " , round(fp.qtde) as QTDEDIA "
                . " , round(sum(fp.qtde) over (partition by to_char(fp.dt, 'MM') order by fp.dt asc)) as QTDEMES "
                . " , round(sum(fp.qtde) over (partition by to_char(fp.dt, 'YYYY') order by fp.dt asc)) as QTDEANO "
                . " from "
                . " vga_frente_plan fp "
                . " where "
                . " fp.frente_id = 73 "
                . " and "
                . " fp.ativagr_id = 554 "
                . " and "
                . " fp.dt <= sysdate ";

        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $rTTDO = $this->Read->fetchAll();

        foreach ($rTTDO as $doc) {
            if ($dataTTDO == $doc['DATA']) {
                $qtdeDiaTTDO = $doc['QTDEDIA'];
                $qtdeMesTTDO = $doc['QTDEMES'];
                $qtdeAnoTTDO = $doc['QTDEANO'];
            }
        }

//////////////////////////////////////////////////////////////////////////////////

        $dados = array(
            "idGrafDispEquip" => 1,
            "valorOperTratorPlanDia" => round(($qtdeHrApontDiaTPCA / $qtdeHrParadaDiaTPCA) * 100),
            "valorCampoTratorPlanDia" => round(($qtdeDiaTPDO / $qtdeHrApontDiaTPCA) * 100),
            "valorOperTratorPlanMes" => round(($qtdeHrApontMesTPCA / $qtdeHrParadaMesTPCA) * 100),
            "valorCampoTratorPlanMes" => round(($qtdeMesTPDO / $qtdeHrApontMesTPCA) * 100),
            "valorOperTratorPlanAno" => round(($qtdeHrApontAnoTPCA / $qtdeHrParadaAnoTPCA) * 100),
            "valorCampoTratorPlanAno" => round(($qtdeAnoTPDO / $qtdeHrApontAnoTPCA) * 100),
            "valorOperCamMudaDia" => round(($qtdeHrApontDiaCTCA / $qtdeHrParadaDiaCTCA) * 100),
            "valorCampoCamMudaDia" => round(($qtdeDiaCTDO / $qtdeHrApontDiaCTCA) * 100),
            "valorOperCamMudaMes" => round(($qtdeHrApontMesCTCA / $qtdeHrParadaMesCTCA) * 100),
            "valorCampoCamMudaMes" => round(($qtdeMesCTDO / $qtdeHrApontMesCTCA) * 100),
            "valorOperCamMudaAno" => round(($qtdeHrApontAnoCTCA / $qtdeHrParadaAnoCTCA) * 100),
            "valorCampoCamMudaAno" => round(($qtdeAnoCTDO / $qtdeHrApontAnoCTCA) * 100),
            "valorOperColhedoraDia" => round(($qtdeHrApontDiaCOCA / $qtdeHrParadaDiaCOCA) * 100),
            "valorCampoColhedoraDia" => round(($qtdeDiaCODO / $qtdeHrApontDiaCOCA) * 100),
            "valorOperColhedoraMes" => round(($qtdeHrApontMesCOCA / $qtdeHrParadaMesCOCA) * 100),
            "valorCampoColhedoraMes" => round(($qtdeMesCODO / $qtdeHrApontMesCOCA) * 100),
            "valorOperColhedoraAno" => round(($qtdeHrApontAnoCOCA / $qtdeHrParadaAnoCOCA) * 100),
            "valorCampoColhedoraAno" => round(($qtdeAnoCODO / $qtdeHrApontAnoCOCA) * 100),
            "valorOperTratorTransbDia" => round(($qtdeHrApontDiaTTCA / $qtdeHrParadaDiaTTCA) * 100),
            "valorCampoTratorTransbDia" => round(($qtdeDiaTTDO / $qtdeHrApontDiaTTCA) * 100),
            "valorOperTratorTransbMes" => round(($qtdeHrApontMesTTCA / $qtdeHrParadaMesTTCA) * 100),
            "valorCampoTratorTransbMes" => round(($qtdeMesTTDO / $qtdeHrApontMesTTCA) * 100),
            "valorOperTratorTransbAno" => round(($qtdeHrApontAnoTTCA / $qtdeHrParadaAnoTTCA) * 100),
            "valorCampoTratorTransbAno" => round(($qtdeAnoTTDO / $qtdeHrApontAnoTTCA) * 100)
        );

        $infor3 = array("dados" => array($dados));
        $res3 = json_encode($infor3);

        return $res3;
    }

}
