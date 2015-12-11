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
        $this->fake_data = [];
        $this->getFromCache($type, $count, $cache_key);
        if ($this->fake_data) return $this->fake_data;

        $this->createNewFakeData($type, $count);
        $this->cacheNewFakeData($cache_key);

        return $this->fake_data;
    }

    /**
     * @param $type
     * @param $count
     * @param $cache_key
     */
    private function getFromCache($type, $count, $cache_key)
    {
        if ($cache_key)
        {
            $this->fake_data = $this->getCachedData($type, $count, $cache_key);
        }
    }

    /**
     * Try to get the data from the cache.
     *
     * @param $type
     * @param $count
     * @param $cache_key
     * @return array|bool
     */
    private function getCachedData($type, $count, $cache_key)
    {
        if (isset($this->cache[$cache_key]))
        {
            return array_slice($this->cache[$cache_key], 0, $count);
        }

        return false;
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