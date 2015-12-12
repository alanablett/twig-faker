<?php

namespace Ablett\TwigFaker;

use Faker\Factory;

class TwigFakerExtension extends \Twig_Extension {

    private $cache;
    private $fake_data;

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'faker';
    }

    /**
     * Callback for twig.
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            'fake' => new \Twig_Function_Method($this, 'fakerData')
        ];
    }

    /**
     * Generate fake data.
     *
     * @param $type
     * @param int $count
     * @return array
     */
    public function fakerData($type, $count = 1, $cache_key = null)
    {
        if (isset($this->cache[$cache_key])) return $this->cache[$cache_key];

        $this->createNewFakeData($type, $count);
        $this->cacheNewFakeData($cache_key);

        return $this->fake_data;
    }

    /**
     * Create the fake data.
     *
     * @param $type
     * @param $count
     */
    private function createNewFakeData($type, $count)
    {
        $faker = Factory::create();
        $this->fake_data = [];

        for ($i = 0; $i < $count; $i++)
        {
            $fake_item = include 'factories/' . $type . '.php';
            array_push($this->fake_data, $fake_item);
        }
    }

    /**
     * Cache the new fake data if a key was sent.
     *
     * @param $cache_key
     */
    private function cacheNewFakeData($cache_key)
    {
        if ($cache_key)
        {
            $this->cache[$cache_key] = $this->fake_data;
        }
    }
}