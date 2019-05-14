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
class GrafPlanReal2DAO extends Conn {
    //put your code here

    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function dados() {

        $this->Conn = parent::getConn();

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

        return $res2;
        
    }

}
