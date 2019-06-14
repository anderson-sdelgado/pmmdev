<?php

require_once('./control/AtualParadaCTR.class.php');

$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

   $atualParadaCTR = new AtualParadaCTR();
    echo $atualParadaCTR->dadosVersao1($info);

endif;