<?php

require('./control/InserirCheckListCTR.class.php');

$inserirCheckListCTR = new InserirCheckListCTR();

if (isset($info)):

    $inserirCheckListCTR->salvarDados($info);
    echo 'GRAVOU-CHECKLIST';

endif;


