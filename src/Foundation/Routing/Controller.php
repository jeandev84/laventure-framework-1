<?php
namespace Laventure\Foundation\Routing;


use Exception;
use Laventure\Component\Container\ContainerAwareInterface;
use Laventure\Component\Container\ContainerInterface;
use Laventure\Component\Database\Manager;
use Laventure\Component\Http\Response\JsonResponse;
use Laventure\Component\Http\Response\RedirectResponse;
use Laventure\Component\Http\Response\Response;
use Laventure\Component\Routing\Router;
use Laventure\Component\Templating\Renderer;


/**
 * @Controller
*/
class Controller implements ContainerAwareInterface
{


    /**
     * @var ContainerInterface
    */
    protected $container;





    /**
     * @var mixed
    */
    protected $layout;




    /**
     * @param ContainerInterface $container
     * @return void
    */
    public function setContainer(ContainerInterface $container)
    {
         $this->container = $container;
    }




    /**
     * @return ContainerInterface
    */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }




    /**
     * @param $id
     * @return mixed
    */
    public function get($id)
    {
        return $this->container->get($id);
    }




    /**
     * @param string $template
     * @param array $data
     * @param Response|null $response
     * @return Response
    */
    public function render(string $template, array $data = [], Response $response = null): Response
    {
        return (function () use ($template, $data, $response) {

            /** @var Renderer $renderer */
            $renderer =  $this->get('view');

            $renderer->withLayout(sprintf('%s.php', $this->layout));
            $output = $renderer->render($template, $data);

            if (! $response) {
                $response = new Response();
            }

            $response->setContent($output);

            return $response;

        })();
    }



    /**
     * @return false|string
    */
    public function getControllerPath()
    {
        return (new \ReflectionClass(get_called_class()))->getFileName();
    }




    /**
     * @param $template
     * @param array $data
     * @return mixed
     * @throws Exception
    */
    public function renderHtml($template, array $data = [])
    {
        return $this->get('view')->render($template, $data);
    }




    /**
     * @param string $path
     * @param int $code
     * @return RedirectResponse
    */
    public function redirectTo(string $path, int $code = 301): RedirectResponse
    {
          return new RedirectResponse($path, $code);
    }




    /**
     * @return RedirectResponse
    */
    public function redirectToHome(): RedirectResponse
    {
        // return $this->redirectTo('/');
    }



    /**
     * @param string $name
     * @param array $parameters
     * @param int $statusCode
     * @return RedirectResponse
    */
    public function redirectToRoute(string $name, array $parameters = [], int $statusCode = 301): RedirectResponse
    {
        $path = $this->get(Router::class)->generate($name, $parameters);

        return $this->redirectTo($path, $statusCode);
    }




    /**
     * @param array $data
     * @param int $statusCode
     * @param array $headers
     * @return JsonResponse
    */
    public function json(array $data, int $statusCode = 200, array $headers = []): JsonResponse
    {
         return new JsonResponse($data, $statusCode, $headers);
    }




    /**
     * @return mixed
    */
    public function database(): Manager
    {
        return $this->get('db.laventure');
    }
}