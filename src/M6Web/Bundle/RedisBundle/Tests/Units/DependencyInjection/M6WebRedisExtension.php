<?php
namespace M6Web\Bundle\RedisBundle\DependencyInjection\tests\units;

use mageekguy\atoum;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use M6Web\Bundle\RedisBundle\DependencyInjection\M6WebRedisExtension as BaseM6WebRedisExtension;
use Symfony\Component\EventDispatcher\EventDispatcher;
use M6Web\Bundle\RedisBundle\EventDispatcher\RedisEvent;


class M6WebRedisExtension extends atoum\test
{

    /**
     * @var BaseM6WebRedisExtension
     */
    protected $extension;
    /**
     * @var ContainerBuilder
     */
    protected $container;

    /**
     *
     */
    protected function initContainer()
    {
        $this->extension = new BaseM6WebRedisExtension();
        $this->container = new ContainerBuilder();
        $this->container->register('event_dispatcher', new EventDispatcher());
        $this->container->registerExtension($this->extension);
        $this->container->setParameter('kernel.debug', true);
    }

    /**
     * @param ContainerBuilder $container
     * @param $resource
     */
    protected function loadConfiguration(ContainerBuilder $container, $resource)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../Fixtures/'));
        $loader->load($resource.'.yml');
    }

    public function testBasicConfiguration()
    {
        $this->initContainer();
        $this->loadConfiguration($this->container, 'basic_config');
        $this->container->compile();

        $this->assert
            ->boolean($this->container->has('m6_redis'))
                ->isIdenticalTo(true)
            ->and()
                ->object($serviceRedis = $this->container->get('m6_redis'))
                    ->isInstanceOf('M6Web\Bundle\RedisBundle\Redis\Redis');
    }

    public function testReconnectConfiguration()
    {
        $this->initContainer();
        $this->loadConfiguration($this->container, 'reconnect_config');
        $this->container->compile();

        $this->assert
            ->boolean($this->container->has('m6_redis'))
                ->isIdenticalTo(true)
            ->and()
                ->object($serviceRedis = $this->container->get('m6_redis'))
                    ->isInstanceOf('M6Web\Bundle\RedisBundle\Redis\Redis');
    }

}