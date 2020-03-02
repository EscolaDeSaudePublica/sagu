<?php

/**
 * Unit test suite class
 *
 * @author Armando Taffarel Neto [taffarel@solis.coop.br]
 *
 * \b Maintainers: \n
 * Armando Taffarel Neto [taffarel@solis.coop.br]
 * Daniel Hartmann [daniel@solis.coop.br]
 *
 * @since
 * Creation date 2011/03/14
 *
 * \b Organization: \n
 * SOLIS - Cooperativa de Soluções Livres \n
 *
 * \b Copyright: \n
 * Copyright (c) 2011 SOLIS - Cooperativa de Soluções Livres \n
 *
 * \b License: \n
 * Licensed under GPLv2 (for further details read the COPYING file or http://www.gnu.org/licenses/gpl.html)
 */

require_once 'PHPUnit/Framework/TestSuite.php';

class MUnitTestSuite extends PHPUnit_Framework_TestSuite
{
    /**
     * Constructs the test suite handler.
     *
     * @param string $name Test suite name
     */
    public function __construct($name)
    {
        $this->setName($name);

        foreach ( glob("tst*.php") as $file )
        {
            require_once "$file";
            $fileName = explode('.', $file);
            $this->addTestSuite($fileName[0]);
        }
    }

    /**
     * Creates the suite.
     */
    public static function suite()
    {
        return new self('MUnitTestSuite');
    }
}

?>