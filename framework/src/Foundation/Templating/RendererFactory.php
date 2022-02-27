<?php
namespace Laventure\Foundation\Templating;


use Laventure\Component\Templating\Contract\ViewFactoryInterface;
use Laventure\Foundation\Templating\Contract\ViewAdapterInterface;
use Laventure\Foundation\Templating\Exception\RendererFactoryException;




/**
 * @RenderFactory
*/
class RendererFactory
{

      /**
       * @var ViewAdapterInterface
       *
       * @var array
      */
      protected $adapters = [];




      /**
       * @param array $adapters
      */
      public function __construct(array $adapters)
      {
            $this->addAdapters($adapters);
      }




      /**
       * @param string $extension
       * @return ViewAdapterInterface
       * @throws RendererFactoryException
      */
      public function createView(string $extension): ViewAdapterInterface
      {
            foreach ($this->adapters as $adapter) {
                 if ($adapter->getExtension() === $extension) {
                     return $adapter;
                 }
            }

            throw new RendererFactoryException("Cannot resolve view extension {$extension}");
      }




      /**
       * @param array $adapters
       * @return void
      */
      private function addAdapters(array $adapters)
      {
          foreach ($adapters as $adapter) {
             if ($adapter instanceof ViewFactoryInterface) {
                $this->adapters[] = $adapter;
             }
          }
      }


}