<?php

require('./control/AtualParadaCTR.class.php');

$atualParadaCTR = new AtualParadaCTR();

echo $retorno = $atualParadaCTR->dados();
