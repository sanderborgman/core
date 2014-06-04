<?php
/**
 * This file is part of Vegas package
 *
 * @author Arkadiusz Ostrycharz <arkadiusz.ostrycharz@gmail.com>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage https://bitbucket.org/amsdard/vegas-phalcon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vegas\Tests\Task;

use Vegas\Task\AssetsTask;
use Vegas\Tests\Cli\TestCase;

class AssetsTest extends TestCase
{
    public function testPublishAction()
    {
        $this->bootstrap->setArguments(array(
            0 => 'cli/cli.php',
            1 => 'vegas:assets',
            2 => 'publish'
        ));

        ob_start();

        $this->bootstrap->setup()->run();
        $result = ob_get_contents();

        ob_clean();

        unlink(TESTS_ROOT_DIR.'/fixtures/public/assets/css/another.css');
        unlink(TESTS_ROOT_DIR.'/fixtures/public/assets/css/test.css');

        $this->assertEquals("Copying assets..\nCannot copy ".TESTS_ROOT_DIR."/fixtures/vendor/vegas-cmf/example/assets/js/example.js. File already exists.\nDone.", $result);
    }
}