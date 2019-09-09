<?php

require('./control_v1/ItemCheckListCTR.class.php');

$itemCheckListCTR = new ItemCheckListCTR();

echo $itemCheckListCTR->dados();
