<?php
/**
 * The AbstractModule class provides a best-practice Module class for configuring a module. In some cases, the Module
 * class in the extending module may only need to extend AbstractModule to provide all of the required functionality.
 *
 * The AbstractModule class in Payum is extracted from TccAbstractModule (http://github.com/tccltd/TccAbstractModule).
 * If you have TccAbstractModule in your project, you could extend this in Payum's Module class instead for DRYer code.
 *
 * Note that for compatibility to be maintained with TccAbstractModule, you should try to avoid putting Payum-specific
 * content in this file, instead placing it as an extension in Payum's Module.php. It would also be good to feedback
 * any improvements to the AbstractModule class into the original TccAbstractModule so that others may benefit.
 */

namespace Payum\PayumModule\Module;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use Zend\Stdlib\ArrayUtils;

abstract class AbstractModuleNoTraits implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ControllerProviderInterface,
    ServiceProviderInterface,
    ViewHelperProviderInterface
{
    /**
     * In order to provide functionality, we need to know the root directory of the Module that is extending this
     * AbstractModule. Usually, this is straightforward as the Module.php of the extending Module is stored in this
     * root directory. This is not always the case, however. For example, a developer may wish to adhere strictly to
     * PSR-0, storing Module.php in (for example) ./src/MyModule/Module.php and using a placeholder file at
     * ./Module.php that includes the PSR-0 compliant version of the file.
     *
     * Unfortunately, this makes it very hard to get the module root directory. This variable allows the developer to
     * specify the relative location of the Module's root directory in relation to the Module.php file.
     *
     * By default, we assume that Module.php is IN the module's base directory and do not set a relative path.
     *
     * @var string
     */
    protected $relativeModuleDir = '';

    /**
     * Return an array to configure a default autoloader instance.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        $autoloaderArray = array();

        // If this module has a defined classmap, add a classmap autoloader.
        $classmapPath = $this->getDir() . '/' . $this->relativeModuleDir . 'autoload_classmap.php';
        if (file_exists($classmapPath)) {
            $autoloaderArray['Zend\Loader\ClassMapAutoloader'] = array(
                $classmapPath
            );
        }

        // Fallback to a PSR-0 autoloader.
        $autoloaderArray['Zend\Loader\StandardAutoloader'] = array(
            'namespaces' => array(
                $this->getNamespace() =>
                    $this->getDir() . '/' . $this->relativeModuleDir . 'src/' . $this->getNamespace(),
            ),
        );

        return $autoloaderArray;
    }

    /**
     * Return and merge configuration for this module from the default location of ./config/module.config{,.*}php.
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        $configFiles = glob(
                $this->getDir() . '/' . $this->relativeModuleDir . 'config/module.config{,.*}.php',
                GLOB_BRACE
        );

        // glob() returns false on error. On some systems, glob() will return false (instead of an empty array) if no
        // files are found. Treat both in the same way - no config will be loaded.
        $configFiles = $configFiles ?: array();

        $config = array();
        foreach ($configFiles as $configFile) {
            $config = ArrayUtils::merge($config, include $configFile);
        }
        return $config;
    }

    /**
     * Return and merge controller configuration for this module from the default location of
     * ./config/service/controller.config{,.*}php.
     *
     * @return array|\Traversable
     */
    public function getControllerConfig()
    {
        $configFiles = glob(
                $this->getDir() . '/' . $this->relativeModuleDir . 'config/service/controller.config{,.*}.php',
                GLOB_BRACE
        );

        // glob() returns false on error. On some systems, glob() will return false (instead of an empty array) if no
        // files are found. Treat both in the same way - no config will be loaded.
        $configFiles = $configFiles ?: array();

        $config = array();
        foreach ($configFiles as $configFile) {
            $config = ArrayUtils::merge($config, include $configFile);
        }
        return $config;
    }

    /**
     * Return and merge controller configuration for this module from the default location of
     * ./config/service/controller.config{,.*}php.
     *
     * @return array|\Traversable
     */
    public function getServiceConfig()
    {
        $configFiles = glob(
                $this->getDir() . '/' . $this->relativeModuleDir . 'config/service/service.config{,.*}.php',
                GLOB_BRACE
        );

        // glob() returns false on error. On some systems, glob() will return false (instead of an empty array) if no
        // files are found. Treat both in the same way - no config will be loaded.
        $configFiles = $configFiles ?: array();

        $config = array();
        foreach ($configFiles as $configFile) {
            $config = ArrayUtils::merge($config, include $configFile);
        }
        return $config;
    }

    /**
     * Return and merge viewhelper configuration for this module from the default location of
     * ./config/service/viewhelper.config{,.*}php.
     *
     * @return array|\Traversable
     */
    public function getViewHelperConfig()
    {
        $configFiles = glob(
                $this->getDir() . '/' . $this->relativeModuleDir . 'config/service/viewhelper.config{,.*}.php',
                GLOB_BRACE
        );

        // glob() returns false on error. On some systems, glob() will return false (instead of an empty array) if no
        // files are found. Treat both in the same way - no config will be loaded.
        $configFiles = $configFiles ?: array();

        $config = array();
        foreach ($configFiles as $configFile) {
            $config = ArrayUtils::merge($config, include $configFile);
        }
        return $config;
    }

    /**
     * Because __DIR__ in a child class returns the directory for the parent class, this workaround is required to get
     * the directory of the child class.
     *
     * @returns string
     */
    protected function getDir()
    {
        $reflectionClass = new \ReflectionClass(get_class($this));
        return dirname($reflectionClass->getFileName());
    }

    /**
     * Because __NAMESPACE__ in a child class returns the namespace for the parent class, this workaround is required
     * to get the namespace of the child class.
     *
     * @returns string
     */
    protected function getNamespace()
    {
        $reflectionClass = new \ReflectionClass(get_class($this));
        return dirname($reflectionClass->getFileName());
    }
}
