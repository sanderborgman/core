<?php
/**
 * This file is part of Vegas package
 *
 * @author Slawomir Zytko <slawomir.zytko@gmail.com>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage https://bitbucket.org/amsdard/vegas-phalcon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Vegas\Tests\Db;

use Phalcon\DI;
use Vegas\Db\Decorator\CollectionAbstract;
use Vegas\Db\Mapping\Json;
use Vegas\Db\MappingManager;

class Fake extends CollectionAbstract
{
    public function getSource()
    {
        return 'fake';
    }

    protected $mappings = array(
        'somedata'  =>  'json',
        'somecamel' =>  'camelize'
    );
}

class MappingTest extends \PHPUnit_Framework_TestCase
{
    public function testMappingManager()
    {
        //define mappings
        $mappingManager = new MappingManager();
        $mappingManager->add(new Json());
        $mappingManager->add('\Vegas\Db\Mapping\Camelize');

        $this->assertNotEmpty(MappingManager::find('json'));
        $this->assertInstanceOf('\Vegas\Db\MappingInterface', MappingManager::find('json'));

        $this->assertNotEmpty(MappingManager::find('camelize'));
        $this->assertInstanceOf('\Vegas\Db\MappingInterface', MappingManager::find('camelize'));
    }

    public function testResolveMappings()
    {
        DI::getDefault()->get('mongo')->selectCollection('fake')->remove(array());

        $someData = json_encode(array(1,2,3,4,5,6));
        $fake = new Fake();
        $fake->somedata = $someData;
        $nonCamelText = 'this_is_non_camel_case_text';
        $fake->somecamel = $nonCamelText;
        $this->assertTrue($fake->save());

        $fakeDoc = Fake::findFirst();

        $this->assertInternalType('array', $fakeDoc->readMapped('somedata'));
        $this->assertEquals(\Phalcon\Text::camelize($nonCamelText), $fakeDoc->readMapped('somecamel'));

        $this->assertEquals($nonCamelText, $fakeDoc->somecamel);
        $this->assertEquals($someData, $fakeDoc->somedata);
        $this->assertEquals($someData, $fakeDoc->readAttribute('somedata'));
        $this->assertEquals($nonCamelText, $fakeDoc->readAttribute('somecamel'));

        $ownMappedValues = array(
            '_id'   =>  $fakeDoc->readMapped('_id'),
            'somedata'   =>  $fakeDoc->readMapped('somedata'),
            'somecamel'   =>  $fakeDoc->readMapped('somecamel'),
        );
        $mappedValues = $fakeDoc->toMappedArray();

        $this->assertEquals($mappedValues['somedata'], $ownMappedValues['somedata']);
        $this->assertEquals($mappedValues['somecamel'], $ownMappedValues['somecamel']);
    }
} 