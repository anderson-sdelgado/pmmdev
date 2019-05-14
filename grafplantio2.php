<?php

require('./dao/GrafProd2DAO.class.php');
require('./dao/GrafPlanReal2DAO.class.php');
require('./dao/GrafDispCampo2DAO.class.php');
require('./dao/GrafQual2DAO.class.php');

$grafProdDAO = new GrafProd2DAO();
$grafPlanRealDAO = new GrafPlanReal2DAO();
$grafDispCampoDAO = new GrafDispCampo2DAO();
$grafQualDAO = new GrafQual2DAO();

$ret1 = $grafProdDAO->dados();
$ret2 = $grafPlanRealDAO->dados();
$ret3 = $grafDispCampoDAO->dados();
$ret4 = $grafQualDAO->dados();

echo $ret1 . "#" . $ret2 . "|" . $ret3 . "?" . $ret4;