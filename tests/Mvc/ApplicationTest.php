<?php
/**
 * This file is part of Vegas package
 *
 * @author Slawomir Zytko <slawek@amsterdam-standard.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage http://vegas-cmf.github.io
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Vegas\Tests\Mvc;

use Phalcon\DI;
use Vegas\Mvc\Application;
use Vegas\Mvc\Module\Loader as ModuleLoader;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    public function testModuleRegister()
    {
        $moduleLoader = new ModuleLoader(DI::getDefault());
        $modules = $moduleLoader->dump(
            TESTS_ROOT_DIR . '/fixtures/app/modules/',
            TESTS_ROOT_DIR . '/fixtures/app/config/'
        );

        $app = new Application();
        $app->registerModules($modules);

        $this->assertSameSize($modules, $app->getModules());

        $this->assertTrue(class_exists('Test\\Models\\Fake'));
        $this->assertTrue(class_exists('Test\\Components\\Fake'));
        $this->assertTrue(class_exists('Test\\Services\\Fake'));
    }

} 