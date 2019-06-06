<?php

require('./control/PneuCTR.class.php');

$pneuDAO = new PneuDAO();
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

    echo $retorno = $pneuDAO->dados($info);

endif;

