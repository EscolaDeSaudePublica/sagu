<?php
/**
 * <--- Copyright 2005-2011 de Solis - Cooperativa de Soluções Livres Ltda. e
 * Univates - Centro Universitário.
 * 
 * Este arquivo é parte do programa Gnuteca.
 * 
 * O Gnuteca é um software livre; você pode redistribuí-lo e/ou modificá-lo
 * dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação
 * do Software Livre (FSF); na versão 2 da Licença.
 * 
 * Este programa é distribuído na esperança que possa ser útil, mas SEM
 * NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer MERCADO
 * ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em
 * português para maiores detalhes.
 * 
 * Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título
 * "LICENCA.txt", junto com este programa, se não, acesse o Portal do Software
 * Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a
 * Fundação do Software Livre (FSF) Inc., 51 Franklin St, Fifth Floor, Boston,
 * MA 02110-1301, USA --->
 * 
 * @author Moises Heberle [moises@solis.coop.br]
 *
 * @version $Id$
 *
 * \b Maintainers \n
 * Eduardo Bonfandini [eduardo@solis.coop.br]
 * Jamiel Spezia [jamiel@solis.coop.br]
 *
 * @since
 * Class created on 02/08/2008
 *
 **/
class GrdExemplaryStatusHistory extends GGrid
{
    public $MIOLO;
    public $module;
    public $action;
    public $busMaterial;

    public function __construct($data)
    {
        $this->MIOLO       = MIOLO::getInstance();
        $this->module      = MIOLO::getCurrentModule();
        $this->action      = MIOLO::getCurrentAction();
        $this->busMaterial = $this->MIOLO->getBusiness($this->module, 'BusMaterial');

        $columns = array(
            new MGridColumn(_M('Número de controle', $this->module),   MGrid::ALIGN_RIGHT, null, null, true, null, true),
            new MGridColumn(_M('Número do exemplar', $this->module),      MGrid::ALIGN_RIGHT, null, null, true, null, true),
            new MGridColumn(_M('Título', $this->module),            MGrid::ALIGN_LEFT,  null, null, true, null, true),
            new MGridColumn(_M('Autor', $this->module),           MGrid::ALIGN_LEFT,  null, null, true, null, true),
            new MGridColumn(_M('Estado do exemplar', $this->module), MGrid::ALIGN_LEFT,  null, null, true, null, true),
            new MGridColumn(_M('Unidade de biblioteca', $this->module),     MGrid::ALIGN_LEFT,  null, null, true, null, true),
            new MGridColumn(_M('Data', $this->module),             MGrid::ALIGN_LEFT,  null, null, true, null, true, MSort::MASK_DATETIME_BR),
            new MGridColumn(_M('Operador', $this->module),         MGrid::ALIGN_LEFT,  null, null, true, null, true),
        );

        parent::__construct($data, $columns, $this->MIOLO->getCurrentURL(), LISTING_NREGS, 0, 'gridExemplaryStatusHistory');

        $this->setIsScrollable();
        $this->setRowMethod($this, 'checkValues');
    }


    public function checkValues($i, $row, $actions, $columns)
    {
    	$data = new GDate($columns[6]->control[$i]->value);
    	$columns[6]->control[$i]->value = $data->getDate(GDate::MASK_TIMESTAMP_USER);
    }
}
?>
