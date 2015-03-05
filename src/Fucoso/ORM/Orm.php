<?php

namespace Fucoso\ORM;

use Evenement\EventEmitter;

use Fucoso\ORM\Config\ArrayLoader;
use Fucoso\ORM\Config\JsonLoader;
use Fucoso\ORM\Config\YamlLoader;
use Fucoso\ORM\Config\PostProcessor;
use Fucoso\ORM\Config\Configuration;
use Fucoso\ORM\Database\Database;
use Fucoso\ORM\Database\Factory;

use Pimple\Container;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Exception\FileLoaderLoadException;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;

class Orm extends Container
{
    public function __construct()
    {
        // One or more configurations can be given as constructor arguments
        $this['config.input'] = func_get_args();

        $this['config.loader'] = function() {
            return new DelegatingLoader(new LoaderResolver([
                new ArrayLoader(),
                new JsonLoader(),
                new YamlLoader(),
            ]));
        };

        $this['config.processor'] = function () {
            return new Processor();
        };

        $this['config.tree'] = function () {
            $configuration = new Configuration();
            $builder = $configuration->getConfigTreeBuilder();
            return $builder->buildTree();
        };

        $this['config.postprocessor'] = function () {
            return new PostProcessor();
        };

        $this['config'] = function() {
            // Load all given configurations
            $configs = [];
            foreach ($this['config.input'] as $raw) {
                $configs[] = $this['config.loader']->load($raw);
            }

            // Combine them and validate
            $config = $this['config.processor']->process(
                $this['config.tree'],
                $configs
            );

            // Additional postprocessing to handle
            return $this['config.postprocessor']->processConfig($config);
        };

        // Event emitter
        $this['emitter'] = function () {
            return new EventEmitter();
        };

        // Parser for model metadata
        $this['meta.builder'] = function () {
            return new MetaBuilder();
        };

        // Model metadata cache
        $this['meta.cache'] = function () {
            return new \ArrayObject();
        };

        // Database manager
        $this['database'] = function() {
            return new Database(
                $this['config']['databases'],
                $this['emitter']
            );
        };
    }

    /**
     * Constructs a QuerySet for a given model.
     *
     * @param string $model The name of the model class.
     *
     * @return Fucoso\ORM\QuerySet
     */
    public function objects($model)
    {
        $meta = $this->getModelMeta($model);

        $driver = $this['config']['databases'][$meta->database]['driver'];

        $query = new Query($meta, $this['database'], $driver);

        return new QuerySet($query, $meta);
    }

    /**
     * Returns the Meta object for a given Model.
     *
     * Results are cached to avoid multiple parsing.
     *
     * @param  string $model Model class name.
     * @return Meta          Model's meta
     */
    public function getModelMeta($model)
    {
        if (!isset($this['meta.cache'][$model])) {
            $this['meta.cache'][$model] = $this['meta.builder']->build($model);
        }

        return $this['meta.cache'][$model];
    }
}
