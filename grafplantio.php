<?php

require('./dao/GrafProdDAO.class.php');
require('./dao/GrafPlanRealDAO.class.php');
require('./dao/GrafDispCampoDAO.class.php');
require('./dao/GrafQualDAO.class.php');

$grafProdDAO = new GrafProdDAO();
$grafPlanRealDAO = new GrafPlanRealDAO();
$grafDispCampoDAO = new GrafDispCampoDAO();
$grafQualDAO = new GrafQualDAO();

$ret1 = $grafProdDAO->dados();
$ret2 = $grafPlanRealDAO->dados();
$ret3 = $grafDispCampoDAO->dados();
$ret4 = $grafQualDAO->dados();

echo $ret1 . "#" . $ret2 . "|" . $ret3 . "?" . $ret4;