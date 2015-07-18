<?php

namespace Ablett\TwigFaker;

use Faker\Factory;

class TwigFakerExtension extends \Twig_Extension {

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
    public function fakerData($type, $count = 1)
    {
        $faker = Factory::create();
        $fake_data = [];

        for ($i=0; $i < $count; $i++)
        {
            $fake_item = include 'factories/' . $type . '.php';
            array_push($fake_data, $fake_item);
        }

        return $fake_data;
    }
}