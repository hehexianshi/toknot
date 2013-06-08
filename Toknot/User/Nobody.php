<?php

/**
 * Toknot (http://toknot.com)
 *
 * @copyright  Copyright (c) 2011 - 2013 Toknot.com
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @link       https://github.com/chopins/toknot
 */
namespace Toknot\User;

use Toknot\User\UserControl;

class Nobody extends UserControl{
    protected $userName = 'nobody';
    protected $uid = -1;
    protected $gid = -1;
}

?>