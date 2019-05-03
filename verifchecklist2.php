<?php

require('./dao/VerifCheckList2DAO.class.php');

$verifCheckListDAO = new VerifCheckList2DAO();
$info = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (isset($info)):

    $retorno = $verifCheckListDAO->dados($info['dado']);
    echo $retorno;

endif;


