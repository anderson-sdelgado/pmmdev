<?php

require('./control/ItemCheckListCTR.class.php');

$itemCheckListCTR = new ItemCheckListCTR();

echo $itemCheckListCTR->dados();
