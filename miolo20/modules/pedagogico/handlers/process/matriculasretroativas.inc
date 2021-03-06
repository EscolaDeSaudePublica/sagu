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
 * Basic employee handler.
 * Contains the menus to access person registers
 *
 * Form handler
 *
 * @author Jonas Gualberto Diel [jonas_diel@solis.com.br]
 *
 * @version $Id$
 *
 * \b Maintainers: \n
 * Jonas Gualberto Diel [jonas_diel@solis.com.br]
 * Joao Francisco Padilha Souza [joao@solis.com.br]
 *
 * @since
 * Class created on 29/08/2013
 */
$function = MIOLO::_REQUEST('function');
$ui = $MIOLO->getUI();
$module = MIOLO::getCurrentModule();

$navbar->addOption(_M('Matr�culas', $module), $module, $action);
access::insert('Matr�culas', $action, 'default-16x16.png');

$MIOLO->checkAccess('FrmEmployee', A_INSERT, true, true);

    $i=1;
    $steps[$i++] = new SStepInfo('FrmMatriculasRetroativas', _M('Inscri��o e Turma', $module), $module);
    $steps[$i++] = new SStepInfo('FrmMatriculasRetroativasFase2', _M('Componentes curriculares', $module), $module);
    $steps[$i++] = new SStepInfo('FrmMatriculasRetroativasFase3', _M('Professores', $module), $module);

    $content = SStepByStepForm::getCurrentForm($steps);


$theme->clearContent();
$theme->insertContent($content);
?>
