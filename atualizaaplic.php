<?php

require_once('./control_v1/AtualAplicCTR.class.php');

$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

   $atualAplicCTR = new AtualAplicCTR();
   echo $atualAplicCTR->verAtualAplicVersao1($info);

endif;