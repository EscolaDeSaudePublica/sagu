<?php

$module = SAGU::getFileModule(__FILE__);
$action = MIOLO::getCurrentAction();
$title = _M('Relat�rio de frequ�ncia do residente', $module);
$form = 'FrmRelatorioFrequenciaIndividualDoResidente';

SAGU::handle($module, $action, $title, $form, 'FrmRelatorioFrequenciaIndividualDoResidente', array('checkAccess' => false));