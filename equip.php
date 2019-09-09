<?php

require('./control_v1/EquipCTR.class.php');

$equipCTR = new EquipCTR();

echo $retorno = $equipCTR->dadosVersao1();
