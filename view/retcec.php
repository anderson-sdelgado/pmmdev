<?php

$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

require_once('../control/CECCTR.class.php');

if (isset($info)):

   $cecCTR = new CECCTR();
   echo $cecCTR->buscarCEC($info);

endif;
