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
 * Pupil contracts handler.
 * Shows all the pupil contracts available for enroll
 *
 * @author Leovan Tavares da Silva [leovan@solis.coop.br]
 *
 * @version $Id$
 *
 * \b Maintainers: \n
 * Arthur Lehdermann [arthur@solis.coop.br]
 * Cristian Edson G�hl [cristian@solis.coop.br]
 *
 * @since
 * Class created on 03/05/2011
 */

$home = 'main:professor:gradeBook';
$navbar->addOption(_M('Disciplinas ministradas pelo professor', $module), $module, substr($home, 0, strrpos($home, ':')+1) . 'curricularComponents', null, array('periodId' => MIOLO::_request('periodId')));
$navbar->addOption(_M('Plano de ensino', $module), $module, $home, null, array('function' => 'resetStack'));

$ui = $MIOLO->getUI();
$frmGradeBook = $ui->getForm($module, 'FrmGradeBook');

$theme->clearContent();
$theme->insertContent($frmGradeBook);
?>