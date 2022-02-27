<?php
namespace Laventure\Foundation\Routing;


use Laventure\Component\Http\Response\Response;
use Laventure\Component\Templating\Exception\RendererException;
use Laventure\Component\Templating\Renderer;

/**
 * @DefaultController
*/
class DefaultController extends Controller
{

     /**
      * @param Renderer $renderer
     */
     public function __construct(Renderer $renderer)
     {
           $renderer->withResource(__DIR__ . '/Resources/views');
     }



     /**
      * @return Response
      * @throws RendererException
     */
     public function index(): Response
     {
         return $this->render('default/welcome.php');
     }
}