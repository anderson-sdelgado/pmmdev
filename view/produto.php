<?php

$versao = filter_input(INPUT_GET, 'versao', FILTER_DEFAULT);

require_once('../control/ProdutoCTR.class.php');

$produtoCTR = new ProdutoCTR();

echo $produtoCTR->dados($versao);
