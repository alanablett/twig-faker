<?php

use Ablett\TwigFaker\TwigFakerExtension;
use Ablett\TwigFaker\InvalidFactoryException;

class TwigFakerExtensionTest extends PHPUnit_Framework_TestCase
{
    protected $twigFaker;

    public function setUp()
    {
        TwigFakerExtension::$factoriesPath = 'tests/factories/';

        $this->twigFaker = new TwigFakerExtension;
    }

    /**
     * @test
     */
    public function it_registers_the_extension_name_correctly()
    {
        $name = $this->twigFaker->getName();

        $this->assertEquals('faker', $name);
    }

    /**
     * @test
     */
    public function it_registers_a_fake_function()
    {
        $registeredFunctions = $this->twigFaker->getFunctions();

        $this->assertArrayHasKey('fake', $registeredFunctions);
    }

    /**
     * @test
     *
     * @expectedException Ablett\TwigFaker\InvalidFactoryException
     */
    public function it_throws_an_exception_when_an_invalid_factory_is_used()
    {
        $data = $this->twigFaker->fakerData('does-not-exist');
    }

    /**
     * @test
     */
    public function it_returns_one_fake_item_when_no_count_is_passed()
    {
        $fakePerson = $this->twigFaker->fakerData('person');

        $this->assertCount(1, $fakePerson);
    }

    /**
     * @test
     */
    public function it_returns_fifteen_fake_items_when_the_count_is_passed()
    {
        $fakePeople = $this->twigFaker->fakerData('person', 15);

        $this->assertCount(15, $fakePeople);
    }

    /**
     * @test
     */
    public function it_caches_a_single_fake_item_when_given_a_cache_key()
    {
        $fakePerson = $this->twigFaker->fakerData('person', 1, 'my-cache-key');
        $cachedFakePerson = $this->twigFaker->fakerData('person', 1, 'my-cache-key');

        $this->assertSame($fakePerson, $cachedFakePerson);
    }

    /**
     * @test
     */
    public function it_caches_multiple_fake_item_when_given_a_cache_key()
    {
        $fakePeople = $this->twigFaker->fakerData('person', 35, 'my-cache-key');
        $cachedFakePeople = $this->twigFaker->fakerData('person', 35, 'my-cache-key');

        $this->assertSame($fakePeople, $cachedFakePeople);
    }

    /**
     * @test
     */
    public function it_caches_single_fake_items_with_different_cache_keys()
    {
        $fakePerson = $this->twigFaker->fakerData('person', 1, 'cache-key-one');
        $cachedfakePerson = $this->twigFaker->fakerData('person', 1, 'cache-key-one');

        $anotherFakePerson = $this->twigFaker->fakerData('person', 1, 'cache-key-two');
        $cachedAnotherFakePerson = $this->twigFaker->fakerData('person', 1, 'cache-key-two');

        $this->assertSame($fakePerson, $cachedfakePerson);
        $this->assertSame($anotherFakePerson, $cachedAnotherFakePerson);
        $this->assertNotSame($fakePerson, $anotherFakePerson);
    }

    /**
     * @test
     */
    public function it_allows_the_same_cache_key_to_be_used_for_different_factories()
    {
        $fakePerson = $this->twigFaker->fakerData('person', 1, 'cache-key-one');
        $fakeAnimal = $this->twigFaker->fakerData('animal', 1, 'cache-key-one');

        $this->assertNotSame($fakePerson, $fakeAnimal);
    }
}