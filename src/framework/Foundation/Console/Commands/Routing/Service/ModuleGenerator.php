<?php
namespace Laventure\Foundation\Console\Commands\Routing\Service;



use Laventure\Component\FileSystem\Exception\FileWriterException;
use Laventure\Component\FileSystem\FileSystem;
use Laventure\Component\Routing\Resource\WebResource;
use Laventure\Component\Routing\Router;
use Laventure\Foundation\Service\Generator\StubGenerator;



/**
 * @ModuleGenerator
*/
class ModuleGenerator extends StubGenerator
{



    /**
     * @var Router
    */
    protected $router;




    /**
     * @param FileSystem $fileSystem
     * @param Router $router
    */
    public function __construct(FileSystem $fileSystem, Router $router)
    {
        parent::__construct($fileSystem);

        $this->router = $router;
    }




    /**
     * @return string
    */
    protected function getStubPath(): string
    {
        return realpath(__DIR__ . '/stubs');
    }




    /**
     * @param string $controllerName
     * @param string $actionStub
     * @return bool
    */
    public function generateControllerClass(string $controllerName, string $actionStub = ""): bool
    {

        $params = explode('/', $controllerName);
        $controllerClass = end($params);

        $module = '';

        if (count($params) >= 2) {
            $module = trim(str_replace($controllerClass, '', $controllerName), '/');
            $module = str_replace(['\\', '/'], '\\', $module);
        }

        $stub = $this->generateControllerStub('controller', [
            'ControllerNamespace' => $this->controllerNamespace($module),
            'ControllerClass'     => $controllerClass,
            'GenerateTime'        => date('d/m/Y H:i:s'),
            'ControllerActions'   => $actionStub
        ]);


        $targetPath = $this->controllerPath($controllerName);

        if ($this->fileSystem->exists($targetPath)) {
            $this->addMessageLog(sprintf('Controller (%s) already exist!', $controllerClass));
            return false;
        }

        // Generate controller and actions
        $this->dumpStub($targetPath, $stub);

        $this->addMessageLog(sprintf('Controller "%s" successfully generated.!', $controllerClass));
        return true;
    }




    /**
     * @param string $controllerName
     * @param array $actions
     * @return string
    */
    public function generateActionStubs(string $controllerName, array  $actions): string
    {
        $actionStubs = [];

        foreach ($actions as $actionName) {
            $actionStubs[] = $this->generateControllerStub('action', [
                'ActionName'      => $actionName,
                'ViewPath'        => $this->viewPath($controllerName, $actionName)
            ]);
        }

        return implode("\n\n", $actionStubs);
    }




    /**
     * @param $controllerName
     * @param array $actions
     * @return void
    */
    public function generateControllerWithActions($controllerName, array $actions = []): bool
    {
        if (empty($actions[0])) {
            $actions = ['index'];
        }

        $actionStub = $this->generateActionStubs($controllerName, $actions);

        $this->generateControllerClass($controllerName, $actionStub);
        $this->generateControllerViews($controllerName, $actions);

        return true;
    }


    /**
     * @param string $entityClass
     * @param string $controllerName
     * @return false|void
     * @throws FileWriterException
     */
    public function generateResource(string $entityClass, string $controllerName = '')
    {
         if (! $controllerName) {
             if (! $entityClass) {
                 return "Entity name is required.";
             }
             $resourceName    =  strtolower($entityClass);
             $controllerName  =  sprintf('%sController', $entityClass);
         } else {
             $resourceName = strtolower(str_replace("Controller", "", $controllerName));
         }


         if ($this->router->hasResource($resourceName)) {
           $this->addMessageLog("This resource web '{$entityClass}' already created!\n");
           return false;
         }

         $routeStub = $this->generateStub("resource/web_routes", [
            'ResourceName'       => $resourceName,
            'ResourceController' => $controllerName
        ]);


        if(! $this->fileSystem->write('routes/web.php', $routeStub)) {
            $this->addMessageLog("Something went wrong during write in file 'routes/web.php'");
            return false;
        }

        $actionStub = $this->generateStub('resource/web_actions', [
             'ViewDirectory' => $resourceName
        ]);

        $this->generateControllerClass($controllerName, $actionStub);
        $this->generateControllerViews($controllerName, WebResource::ACTIONS);
    }





    /**
     * @param string $concreteClass
     * @return bool
     * @throws FileWriterException
    */
    public function generateResourceApi(string $concreteClass): bool
    {
         $resourceName    =  strtolower($concreteClass);
         $controllerName  =  sprintf('Api/%sController', $concreteClass);

         if ($this->router->hasResourceApi($resourceName)) {
            $this->addMessageLog("This resource api '{$concreteClass}' already created!\n");
            return false;
         }


         $routeStub = $this->generateStub("resource/api_routes", [
            'ResourceName'       => $resourceName,
            'ResourceController' => $controllerName
         ]);


         if(! $this->fileSystem->write('routes/api.php', $routeStub)) {
            $this->addMessageLog("Something went wrong during write in file 'routes/api.php'");
            return false;
         }

         $actionStub = $this->generateStub('resource/api_actions', [
            'ViewDirectory' => $resourceName
        ]);

        return $this->generateControllerClass($controllerName, $actionStub);
    }



    /**
     * @param $actionName
     * @return string
    */
    protected function makeActionLogicStub($actionName): string
    {
         switch ($actionName) {
             case 'list':
                  // todo something list data
                 break;
             case 'create':
                 // todo something create data
                break;
             case 'edit':
                 // todo something edit data
                break;
             case 'destroy':
                 // todo something destroy
                break;
         }

         return "";
    }




    /**
     * @param $controllerName
     * @param array $actions
     * @return void
    */
    public function generateControllerViews($controllerName, array $actions)
    {
        $this->addMessageLog("View files successfully generated:");

        foreach ($actions as $actionName) {
            $viewPath = $this->viewPath($controllerName, $actionName);
            $this->generateView($viewPath);
            $this->addMessageLog(sprintf('- %s', $viewPath));
        }
    }






    /**
     * @param $path
     * @return void
    */
    protected function generateView($path)
    {
         $stub = $this->generateControllerStub('view', [
             'Menu'               => '',
             'Content'            => ''
         ]);

         $path = sprintf('templates/views/%s', $path);

         if (! $this->fileSystem->exists($path)) {
             $this->dumpStub($path, $stub);
         }
    }



    /**
     * @param string $controllerName
     * @param string $actionName
     * @return string
    */
    private function viewPath(string $controllerName, string $actionName): string
    {
        $directory = str_replace('Controller', '', $controllerName);

        return strtolower(sprintf('%s/%s.php', $directory, $actionName));
    }



    /**
     * @param string $path
     * @return string
    */
    private function controllerPath(string $path): string
    {
        $targetPath = sprintf('%s/%s.php', $this->router->getControllerPath(), $path);

        return $this->resolvedPath($targetPath);
    }




    /**
     * @param string $module
     * @return string
    */
    private function controllerNamespace(string $module = ''): string
    {
        return $this->router->getControllerNamespace() . ($module ? "\\". trim($module, "\\") : '');
    }



    /**
     * @param $path
     * @param $replacements
     * @return string|string[]
    */
    private function generateControllerStub($path, $replacements)
    {
        return $this->generateStub("controller/$path", $replacements);
    }




    /**
     * @param $controllerName
     * @return void
    */
    public function removeControllerClass($controllerName)
    {
         $controllers = $this->router->collection()->getRoutesByController();

         if (\array_key_exists($controllerName, $controllers)) {
               $reflectionClass = new \ReflectionClass($controllerName);
         }
    }

}