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
 * @author Moises Heberle [moises@solis.coop.br]
 *
 * @version $Id$
 *
 * \b Maintainers: \n
 * Moises Heberle [moises@solis.coop.br]
 *
 * @since
 * Class created on 03/01/2011
 *
 **/

$module = SAGU::getFileModule(__FILE__);
$function = MIOLO::_REQUEST('function');
$ui = $MIOLO->getUI();

$MIOLO->trace('file:' . $_SERVER['SCRIPT_NAME']);
$navbar->addOption(_M('Inscri��o', $module), $module, $action);
access::insert('Inscri��o', $action, 'default-16x16.png');

if ( (strlen($function) == 0) || ($function == 'search') )
{
    // faz checkAccess somente se login.check for true
    $MIOLO->checkAccess('FrmSubscription', A_ACCESS, true, true);
    $content = $ui->getForm($module, 'FrmSubscriptionSearch', $data);
}
else
{
    //Faz checkAccess somente se chamada n�o for do m�dulo de servi�o
    if( !SAGU::userIsFromServices() )
    {
        // faz checkAccess somente se login.check for true
        switch ( $function )
        {
            case 'insert':
                if ( $MIOLO->getConf('login.check') == 'true' )
                {
                    $MIOLO->checkAccess('FrmSubscription', A_INSERT, true, true);
                }
            break;
            case 'update':
                if (strlen(MIOLO::_request('event')) > 0)
                {
                    $MIOLO->checkAccess('FrmSubscription', A_UPDATE, true, true);
                }
                else
                {
                    $MIOLO->checkAccess('FrmSubscription', A_ACCESS, true, true);
                }
            break;
            case 'delete':
                $MIOLO->checkAccess('FrmSubscription', A_DELETE, true, true);
            break;
        }
    }
    
    $i=1;
    $steps[$i++] = new SStepInfo('FrmSubscription', _M('Inscri��o', $module), $module);
    // Condicao recebida de FrmSubscription
    if ( strlen(MIOLO::_REQUEST('step1done')) > 0 )
    {
        //Se tiver no m�dulo de servi�o entra no passo de autentica��o de usu�rio
        if( SAGU::userIsFromServices() && (!strlen($MIOLO->getLogin()->id)>0))
        {
            $steps[$i++] = new SStepInfo('FrmSubscriptionAuthentication', _M('Identifica��o', $module), $module);
        }     
        
        if (SprSelectiveProcess::verificarExisteMensagemInicial(MIOLO::_REQUEST('stepId')))
        {
            $steps[$i++] = new SStepInfo('FrmWelcomeMessage', _M('Mensagem de boas vindas', $module), $module);
        }
        
        $steps[$i++] = new SStepInfo('FrmPersonalInformation', _M('Informa��es pessoais', $module), $module);
        // Condicao recebida de FrmSubscription
        if ( !(strlen(MIOLO::_REQUEST('disable_document')) > 0) )
        {
            $steps[$i++] = new SStepInfo('FrmSubscriptionDocument', _M('Documentos necess�rios', $module), $module);
        }
        $steps[$i++] = new SStepInfo('FrmSubscriptionOption', _M('Op��es de curso', $module), $module);
        // Condi��o recebida de FrmSubscription
        if ( !(strlen(MIOLO::_REQUEST('disable_socioeconomic')) > 0) )
        {
            $steps[$i++] = new SStepInfo('FrmSocioeconomicQuestion', _M('Question�rio socioecon�mico', $module), $module);
        }
        $steps[$i++] = new SStepInfo('FrmSubscriptionFinalize', _M('Fim', $module), $module);
    }

    $content = SStepByStepForm::getCurrentForm($steps);
}

$theme->clearContent();
$theme->insertContent($content);
?>