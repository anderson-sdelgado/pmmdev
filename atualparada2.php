<?php

require('./control/ParadaCTR.class.php');

$paradaDAO = new ParadaDAO();
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

echo $retorno = $paradaDAO->dados($info);

endif;