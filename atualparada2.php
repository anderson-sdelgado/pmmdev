<?php

require('./control_v1/AtualParadaCTR.class.php');

$atualParadaCTR = new AtualParadaCTR();

echo $retorno = $atualParadaCTR->dados();
