<?php
/**
 * Gnuteca Default Tesk Testing
 *
 * @author Luiz Gilberto Gregory Filho [luz@solis.coop.br]
 *
 * @version $Id$
 *
 * \b Maintainers: \n
 * Eduardo Bonfandini [eduardo@solis.coop.br]
 * Jamiel Spezia [jamiel@solis.coop.br]
 * Luiz Gregory Filho [luiz@solis.coop.br]
 * Moises Heberle [moises@solis.coop.br]
 *
 * @since
 * Class created on 06/08/2009
 *
 * \b Organization: \n
 * SOLIS - Cooperativa de Solucoes Livres \n
 * The Gnuteca3 Development Team
 *
 * \b CopyLeft: \n
 * CopyLeft (L) 2007 SOLIS - Cooperativa de Solucoes Livres \n
 *
 * \b License: \n
 * Licensed under GPL (for further details read the COPYING file or http://www.gnu.org/copyleft/gpl.html
 *
 * \b History: \n
 * See history in SVN repository: http://gnuteca.solis.coop.br
 *
 */

class comunicarAquisicoes extends GTask
{

    public $busOperationMaterial;


    /**
     * METODO CONSTRUCT É OBRIGATÓRIO, POIS A CLASSE DE SCHEDULE TASK SEMPRE VAI PASSAR O $MIOLO COMO PARAMETRO
     *
     * @param OBJECT $MIOLO
     */
    function __construct($MIOLO, $myTaskId)
    {
        parent::__construct($MIOLO, $myTaskId);
        $this->busOperationMaterial = $MIOLO->getBusiness($MIOLO->getCurrentModule(), 'BusOperationMaterial');
    }


    /**
     * MÉTODO OBRIGATORIO.
     * ESTE METODO SERA CHAMADO PELA CLASSE SCHEDULE TASK PARA EXECUTAR A TAREFA
     *
     * @return boolean
     */
    public function execute()
    {
        $date       = strlen($this->parameters[0]) ? date($this->parameters[0]) : date("Y-m-d");
        //se tiver parametros seta a unidade
        if ( strstr($this->parameters[1], ',') )
        {
            $libraries = explode(',', $this->parameters[1]);
        }
        else 
        {
            $libraries = $this->parameters[1];
        }
        
        $report = $this->busOperationMaterial->notifyAcquisition($date, $libraries, false, true);

        return true;
    }

}

?>