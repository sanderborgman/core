<?php
/**
 * This file is part of Vegas package
 *
 * @author Slawomir Zytko <slawomir.zytko@gmail.com>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage http://vegas-cmf.github.io
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vegas\DI\Scaffolding\Exception;

/**
 * Class MissingScaffoldingException
 * @package Vegas\DI\Scaffolding\Exception
 */
class MissingScaffoldingException extends \Vegas\DI\Scaffolding\Exception
{
    protected $message = 'Scaffolding has not been set';
}
 