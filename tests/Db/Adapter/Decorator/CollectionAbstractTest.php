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
 
namespace Vegas\Tests\Db\Adapter\Decorator;

use Phalcon\Utils\Slug;
use Vegas\Db\Decorator\CollectionAbstract;

class Fake extends CollectionAbstract
{
    public function getSource()
    {
        return 'fake';
    }
}

class CollectionAbstractTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateDocument()
    {
        $data = array(
            'title' =>  'Title test',
            'content'   =>  'Content test',
            'category_id' => new \MongoId()
        );

        $fake = new Fake();
        $this->assertInstanceOf('\Vegas\Db\Decorator\CollectionAbstract', $fake);
        $fake->writeAttributes($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $fake->readAttribute($key));
        }

        $fake->generateSlug($data['title']);

        $slug = new Slug();
        $this->assertEquals($slug->generate($data['title']), $fake->readAttribute('slug'));
        $this->assertEquals($data['title'], $fake->readMapped('title'));
        $this->assertEquals(json_encode($fake->toArray()), json_encode($fake->toMappedArray()));

        $this->assertTrue($fake->save());
    }

    public function testUpdateDocument()
    {
        $fake = Fake::findFirst();
        $fake->title = 'New title';

        $this->assertTrue($fake->save());
        $this->assertInstanceOf('MongoInt32', $fake->updated_at);

        $fake = Fake::findFirst();
        $this->assertEquals('New title', $fake->title);
    }
} 