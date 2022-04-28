<?php

$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

require_once('../control/MotoMecFertCTR.class.php');

if (isset($info)):

    $motoMecFertCTR = new MotoMecFertCTR();
    echo $motoMecFertCTR->salvarBolAbertoMMFert($info);

endif;
