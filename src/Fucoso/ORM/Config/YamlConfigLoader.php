<?php

namespace Fucoso\ORM\Config;

use Symfony\Component\Yaml\Yaml;

/**
 * Loads and decodes YAML configuration files.
 */
class YamlConfigLoader extends FileLoader
{
    public function load($resource, $type = null)
    {
        $data = $this->loadFile($resource);
        return Yaml::parse($data);
    }

    public function supports($resource, $type = null)
    {
        return is_string($resource) &&
            in_array(pathinfo($resource, PATHINFO_EXTENSION), array('yaml', 'yml'));
    }
}
