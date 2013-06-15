<?php

/**
 * Toknot (http://toknot.com)
 *
 * @copyright  Copyright (c) 2011 - 2013 Toknot.com
 * @license    http://toknot.com/LICENSE.txt New BSD License
 * @link       https://github.com/chopins/toknot
 */

namespace Toknot\Process\Exception;

use Toknot\Exception\StandardException;

class ProcessException extends StandardException {

    public function __construct() {
        parent::__construct('Process Opreate Exception');
    }

}

?>
