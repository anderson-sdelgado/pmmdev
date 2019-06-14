<?php

require_once('./control/AtualAplicativoCTR.class.php');

$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

   $atualAplicativoCTR = new AtualAplicativoCTR();
    echo $atualAplicativoCTR->verAtualAplic($info);

endif;