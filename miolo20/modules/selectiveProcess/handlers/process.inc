<?php
/**
 * <--- Copyright 2005-2010 de Solis - Cooperativa de Solu��es Livres Ltda.
 * 
 * Este arquivo � parte do programa Sagu.
 * 
 * O Sagu � um software livre; voc� pode redistribu�-lo e/ou modific�-lo
 * dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o
 * do Software Livre (FSF); na vers�o 2 da Licen�a.
 * 
 * Este programa � distribu�do na esperan�a que possa ser �til, mas SEM
 * NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer MERCADO
 * ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em
 * portugu�s para maiores detalhes.
 * 
 * Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo
 * "LICENCA.txt", junto com este programa, se n�o, acesse o Portal do Software
 * P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a
 * Funda��o do Software Livre (FSF) Inc., 51 Franklin St, Fifth Floor, Boston,
 * MA 02110-1301, USA --->
 * 
 * Selective process register handler.
 * Contains the menus to access register submenus
 *
 * @author Jamiel Spezia [jamiel@solis.coop.br]
 *
 * @version $Id$
 *
 * \b Maintainers: \n
 * Alexandre Heitor Schmidt [alexsmith@solis.coop.br]
 * Arthur Lehdermann [arthur@solis.coop.br]
 * Samuel Koch [samuel@solis.coop.br]
 * Moises Heberle [moises@solis.coop.br]
 *
 * @since
 * Class created on 15/12/2010
 **/

$MIOLO->trace('file:' . $_SERVER['SCRIPT_NAME']);

$function = MIOLO::_request('function');
if ( $function == 'resetStack' )
{
    SAGU::resetStack();
}

$home = 'main:process';
$navbar->addOption(_M('Processo', $module), $module, $home, null, array('function'=>'resetStack'));

if ( !$MIOLO->invokeHandler($module,'process/'.$context->shiftAction()) &&
     ($action != 'main:process:inscription')  
   )
{
    $MIOLO->checkAccess('sprProcess', A_ACCESS, true, true);

    $ui       = $MIOLO->getUI();
    $btnClose = $MIOLO->getActionURL($module,substr($home,0,strrpos($home,':')));
    $icon     = $ui->getImage($module,'process-16x16.png');
    
    $pnlSelectiveProcess = new MActionPanel('pnlSelectiveProcess', _M('Processo', $module), null, $btnClose, $icon);
    $pnlSelectiveProcess->setIconType('small');
    $pnlSelectiveProcess->setControlSize(SAGU::getParameter('BASIC', 'MPANEL_DEFAULT_WIDTH'),SAGU::getParameter('BASIC', 'MPANEL_DEFAULT_HEIGHT'));

    $menuItem = array(  );

    if ( SAGU::getParameter('BASIC', 'MODULE_ACADEMIC_INSTALLED') == 'YES' 
            || SAGU::getParameter('BASIC', 'MODULE_PEDAGOGICO_INSTALLED' == 'YES'))
    {
        $menuItem[] = array(_M('Gerar contrato/inscri��o',$module), 'default-16x16.png', "$home:generateContract");
        $menuItem[] = array(_M('Importar arquivo csv - inscri��es',$module), 'default-16x16.png', "$home:uploadCsvFileSubscription");
        $menuItem[] = array(_M('Importar arquivo csv - notas',$module), 'default-16x16.png', "$home:uploadCsvFileNote");
        $menuItem[] = array(_M('Processo seletivo',$module), 'default-16x16.png', "$home:selectiveProcess");
        $menuItem[] = array(_M('Detalhamento de etapas',$module), 'default-16x16.png', "$home:detailedSteps");
        $menuItem[] = array(_M('Inscri��o',$module), 'default-16x16.png', "$home:subscription");
        $menuItem[] = array(_M('Confirma��o de matr�cula',$module), 'default-16x16.png', "$home:enrollConfirm");
        $menuItem[] = array(_M('Classifica��o na etapa',$module), 'default-16x16.png', "$home:classification");
        $menuItem[] = array(_M('Digita��o de notas',$module), 'default-16x16.png', "$home:evaluationPoints");
        $menuItem[] = array(_M('Aloca��o por sala', $module), 'default-16x16.png', "$home:roomAllocation");
        $menuItem[] = array(_M('Detalhamento de avalia��es',$module), 'default-16x16.png', "$home:detailedEvaluation");
        $menuItem[] = array(_M('Reenvio do email de confirma��o de inscri��o',$module), 'default-16x16.png', "$home:notifySubscribers");
        $menuItem[] = array(_M('Integra��o com o m�dulo de resid�ncia',$module), 'default-16x16.png', "$home:generateResidentLink");
        $menuItem[] = array(_M('Chamada de suplentes',$module), 'default-16x16.png', "$home:sprSubstitutesCall");
    }

    $sortMenuItem = SAGU::arraySort($menuItem, 0);
    foreach ( $sortMenuItem as $m )
    {
       $pnlSelectiveProcess->addAction($m[0], $ui->getImage($module,$m[1]), $module, $m[2]);
    }

    $theme->clearContent();
    $theme->insertContent($pnlSelectiveProcess);
}
?>