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
 * Finacne process handler.
 * Contains the menus to access process submenus
 *
 * @author Alexandre Heitor Schmidt [alexsmith@solis.coop.br]
 *
 * @version $Id$
 *
 * \b Maintainers: \n
 * Alexandre Heitor Schmidt [alexsmith@solis.coop.br]
 * Daniel Afonso Heisler [daniel@solis.coop.br]
 * Eduardo Beal Miglioransa [eduardo@solis.coop.br]
 * Jamiel Spezia [jamiel@solis.coop.br]
 * Samuel Koch [samuel@solis.coop.br]
 * William Prigol Lopes [william@solis.coop.br]
 * Armando Taffarel Neto [taffarel@solis.coop.br]
 * Fabiano Tomasini [fabiano@solis.coop.br]
 *
 * @since
 * Class created on 07/12/2005
 *
 **/

$MIOLO->trace('file:' . $_SERVER['SCRIPT_NAME']);

$function = MIOLO::_request('function');
if ( $function == 'resetStack' )
{
    SAGU::resetStack();
}

$home = 'main:process';
$navbar->addOption(_M('Processo', $module), $module, $home, null, array('function'=>'resetStack'));

if ( !$MIOLO->invokeHandler($module,'process/'.$context->shiftAction()) )
{
    $MIOLO->checkAccess('finProcess', A_ACCESS, true, true);
    
    $ui       = $MIOLO->getUI();
    $btnClose = $MIOLO->getActionURL($module, substr($home,0,strrpos($home,':')));

    $icon       = $ui->getImage($module, 'process-16x16.png');
    $pnlFinance = new MActionPanel('pnlFinance', _M('Processo', $module), null, $btnClose, $icon);
    $pnlFinance->setIconType('small');
    $pnlFinance->setControlSize(SAGU::getParameter('BASIC', 'MPANEL_DEFAULT_WIDTH'), SAGU::getParameter('BASIC', 'MPANEL_DEFAULT_HEIGHT'));

    $menuItem = array(
        array(_M('Previs�o de lan�amento',               $module), 'folder-16x16.png',                      "$home:incomeForecast"),
        array(_M('T�tulo a receber',            $module), 'folder-16x16.png',                      "$home:receivableInvoice"),
        array(_M('Desconto na folha de pagamento',              $module), 'payrollDiscount-16x16.png',             "$home:payrollDiscount"),
        array(_M('Cancelamento de t�tulos',          $module), 'payrollDiscount-16x16.png',             "$home:invoiceCancellation"),
        array(_M('Imprimir t�tulo',                 $module), 'printInvoice-16x16.png',                "$home:printInvoice"),
        array(_M('Liberar juros e multas',$module), 'release-16x16.png',                     "$home:release"),
        array(_M('Remessa e retorno',         $module), 'folder-16x16.png',                      "$home:remittanceReturn", null, array('function'=>'resetStack')),
        array(_M('Gera��o em lote',             $module), 'folder-16x16.png',                      "$home:generationInLot", null, array('function'=>'resetStack')),
        array(_M('Caixa',                       $module), 'folder-16x16.png',                      "$home:counter"),                       
        array(_M('SPC',                           $module), 'folder-16x16.png',                      "$home:spc", null, array('function'=>'resetStack')),
        array(_M('Exclus�o de matr�culas n�o pagas', $module), 'sagu-16x16.png',  "$home:unpaidEnrollDeletion"),
        array(_M('Negocia��o', $module), 'sagu-16x16.png', "$home:negotiation"),
        array(_M('Validador de comprovante de pagamento', $module), 'sagu-16x16.png', "$home:validateInvoicePayment"),
     );

    $sortMenuItem = SAGU::arraySort($menuItem, 0);
    foreach ( $sortMenuItem as $m )
    {
        $pnlFinance->addAction($m[0], $ui->getImage($module,$m[1]), $module, $m[2], $m[3], $m[4]);
    }

    $theme->clearContent();
    $theme->insertContent($pnlFinance);
}

?>
