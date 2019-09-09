<?php

require('./control_v1/MotoristaCTR.class.php');

$motoristaCTR = new MotoristaCTR();

echo $motoristaCTR->dados();
