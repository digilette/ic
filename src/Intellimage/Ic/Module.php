<?php

namespace Intellimage\Ic;

use Zend\Console\Adapter\AdapterInterface as ConsoleAdapterInterface;
use Zend\EventManager\EventInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Module implements ConsoleUsageProviderInterface, AutoloaderProviderInterface, ConfigProviderInterface
{
    const NAME    = 'IC - Intellimage command line Tool';
    
    /**
     * @var ServiceLocatorInterface
     */
    protected $sm;

    
    private $_application = null;
    
    private $_config = null;


    public function onBootstrap(EventInterface $e)
    {
        $this->_application = $e->getApplication();
        $this->sm = $this->_application->getServiceManager();
    }

    public function getConfig($configPath = null, $asObjects=false)
    {
        if (!isset($this->_config)) {
            $this->_config = include __DIR__ . '/../../../config/module.config.php';
        }
        $config = $this->_config;
        if (isset($configPath)) {
            $config = $this->_config;
            $path = explode('/', $configPath);
            while (count($path) && isset($config[$path[0]])) {
                $config = $config[array_shift($path)];
            }
            if (count($path)) {
                $config = null;
            }
        }
        
        if ($asObjects) {
            $config = json_decode(json_encode($config));
        }
        return $config;
    }
    
    public function getApplication()
    {
        return $this->_application;
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }

    public function getConsoleBanner(ConsoleAdapterInterface $console)
    {
        return self::NAME;
    }

    public function getConsoleUsage(ConsoleAdapterInterface $console)
    {
        $config = $this->sm->get('config');
        if(!empty($config['IC']) && !empty($config['IC']['disable_usage'])){
            return null; // usage information has been disabled
        }
        
        return array(
            'Module creation:',
            'create module <name> [<path>]'     => 'create a module',
            array('<name>', 'The name of the module to be created'),
            array('<path>', 'The root path of a ZF2 application where to create the module'),
            
            'Model creation:',
            'create varien_model [model name]'   => 'create a varien model',
        );
    }
}
