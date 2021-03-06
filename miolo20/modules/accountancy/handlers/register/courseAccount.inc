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
 * Course account handler.
 * Contains the menus to access course account registers
 *
 * @author Armando Taffarel Neto [taffarel@solis.coop.br]
 *
 * @version $Id$
 *
 * \b Maintainers: \n
 * Armando Taffarel Neto [taffarel@solis.coop.br]
 * Alexandre Heitor Schmidt [alexsmith@solis.coop.br]
 * Daniel Afonso Heisler [daniel@solis.coop.br]
 * Jamiel Spezia [jamiel@solis.coop.br]
 * Samuel Koch [samuel@solis.coop.br]
 * William Prigol Lopes [william@solis.coop.br]
 *
 * @since
 * Class created on 26/12/2005
 *
 **/

$MIOLO->trace('file:' . $_SERVER['SCRIPT_NAME']);

$home = 'main:register:courseAccount';
$navbar->addOption(_M('Definir conta cont�bil e centro de custos de um curso', $module), $module, $home);

$MIOLO->getClass('basic', 'access');
access::insert(_M('Definir conta cont�bil e centro de custos de um curso', $module), $home, 'courseAccount-16x16.png');
$data->home = $home;

$function = MIOLO::_request('function');

$ui = $MIOLO->getUI();
if ( (strlen($function) == 0) || ($function == 'search') )
{
    $MIOLO->checkAccess('FrmCourseAccount', A_ACCESS, true, true);
    
    $frmCourseAccountSearch = $ui->getForm($module,'FrmCourseAccountSearch', $data);
    $frmCourseAccountSearch->setClose($MIOLO->getActionURL($module,substr($home, 0, strrpos($home, ':'))));

    $content = $frmCourseAccountSearch;
}
else
{
    switch ( $function )
    {
        case 'insert':
            $MIOLO->checkAccess('FrmCourseAccount', A_INSERT, true, true);
        break;
        case 'update':
            if (strlen(MIOLO::_request('event')) > 0)
            {
                $MIOLO->checkAccess('FrmCourseAccount', A_UPDATE, true, true);
            }
            else
            {
                $MIOLO->checkAccess('FrmCourseAccount', A_ACCESS, true, true);
            }
        break;
        case 'delete':
            $MIOLO->checkAccess('FrmCourseAccount', A_DELETE, true, true);
        break;
    }

    $frmCourseAccount = $ui->getForm( $module, 'FrmCourseAccount' );

    $content = $frmCourseAccount;
}

$theme->clearContent();
$theme->insertContent($content);

?>
