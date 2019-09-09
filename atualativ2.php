<?php

require_once('./control_v1/AtualAtivCTR.class.php');

$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

   $atualAtivCTR = new AtualAtivCTR();
    echo $atualAtivCTR->dados($info);

endif;