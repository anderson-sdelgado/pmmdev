<?php

require('./control/EquipCTR.class.php');

$equipCTR = new EquipCTR();

echo $retorno = $equipCTR->dadosVersao1();
