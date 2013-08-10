<?php

namespace Intellimage\Ic\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\ConsoleModel;
use ZFTool\Model\Skeleton;
use ZFTool\Model\Utility;
use Zend\Console\ColorInterface as Color;
use Zend\Code\Generator;
use Zend\Code\Reflection;

class CreateController extends AbstractActionController
{
    public function moduleAction()
    {
        $request = $this->getRequest();
        $name    = $request->getParam('name');
        $console = $this->getServiceLocator()->get('console');
        $console->writeLine("Creating module $name", Color::LIGHT_GREEN);
        var_dump($request->getParams());
        var_dump($console);
        echo "\n";
        return false;
        die();
        $console = $this->getServiceLocator()->get('console');
        $tmpDir  = sys_get_temp_dir();
        $request = $this->getRequest();
        $name    = $request->getParam('name');
        $path    = rtrim($request->getParam('path'), '/');

        if (empty($path)) {
            $path = '.';
        }

        if (!file_exists("$path/module") || !file_exists("$path/config/application.config.php")) {
            return $this->sendError(
                "The path $path doesn't contain a ZF2 application. I cannot create a module here."
            );
        }
        if (file_exists("$path/module/$name")) {
            return $this->sendError(
                "The module $name already exists."
            );
        }

        $name = ucfirst($name);
        mkdir("$path/module/$name");
        mkdir("$path/module/$name/config");
        mkdir("$path/module/$name/src");
        mkdir("$path/module/$name/src/$name");
        mkdir("$path/module/$name/src/$name/Controller");
        mkdir("$path/module/$name/view");

        // Create the Module.php
        file_put_contents("$path/module/$name/Module.php", Skeleton::getModule($name));

        // Create the module.config.php
        file_put_contents("$path/module/$name/config/module.config.php", Skeleton::getModuleConfig($name));

        // Add the module in application.config.php
        $application = require "$path/config/application.config.php";
        if (!in_array($name, $application['modules'])) {
            $application['modules'][] = $name;
            copy ("$path/config/application.config.php", "$path/config/application.config.old");
            $content = <<<EOD
<?php
/**
 * Configuration file generated by ZFTool
 * The previous configuration file is stored in application.config.old
 *
 * @see https://github.com/zendframework/ZFTool
 */

EOD;

            $content .= 'return '. Skeleton::exportConfig($application) . ";\n";
            file_put_contents("$path/config/application.config.php", $content);
        }
        if ($path === '.') {
            $console->writeLine("The module $name has been created", Color::GREEN);
        } else {
            $console->writeLine("The module $name has been created in $path", Color::GREEN);
        }
    }

    /**
     * Send an error message to the console
     *
     * @param  string $msg
     * @return ConsoleModel
     */
    protected function sendError($msg)
    {
        $m = new ConsoleModel();
        $m->setErrorLevel(2);
        $m->setResult($msg . PHP_EOL);
        return $m;
    }
}