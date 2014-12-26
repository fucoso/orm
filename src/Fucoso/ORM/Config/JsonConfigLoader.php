<?php

namespace Fucoso\ORM\Config;

use Fucoso\ORM\Helper\Json;

/**
 * Loads and decodes JSON configuration files.
 */
class JsonConfigLoader extends FileLoader
{
    public function load($resource, $type = null)
    {
        $data = $this->loadFile($resource);

        return Json::parse($data, true);
    }

    public function supports($resource, $type = null)
    {
        return is_string($resource) &&
            pathinfo($resource, PATHINFO_EXTENSION) === 'json';
    }
}
