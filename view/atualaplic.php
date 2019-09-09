<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

require_once('../control/AtualAplicCTR.class.php');

if (isset($info)):

   $atualAplicCTR = new AtualAplicCTR();
   echo $atualAplicCTR->atualAplic($versao, $info);

endif;