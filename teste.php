<?php

$dado = array();
$dado[] = array("equip" => "a", "os" => "b");
$dado[] = array("equip" => "ac", "os" => "bd");

$dados = array("dados"=>$dado);
echo json_encode($dados);