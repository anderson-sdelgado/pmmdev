<?php

require_once './dao/AjusteDataHoraDAO.class.php';

$ajusteDataHoraDAO = new AjusteDataHoraDAO();

echo $ajusteDataHoraDAO->dataHoraNroEquip(245, "10/05/2019 13:25");