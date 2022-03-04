<?php
namespace Laventure\Component\Templating\Service;


use Laventure\Component\Templating\Contract\ViewFactoryInterface;

/**
 * @Breadcrumb
*/
class Breadcrumb
{



     /**
      * @var ViewFactoryInterface
     */
     protected $view;




     /**
      * @var string
     */
     protected $template;




     /**
      * @var string
     */
     protected $separator = '/';



     /**
      * @var array
     */
     protected $items = [];




     /**
      * @param ViewFactoryInterface $view
     */
     public function __construct(ViewFactoryInterface $view)
     {
          $this->view = $view;
     }




     /**
      * @param string $template
      * @return $this
     */
     public function withTemplate(string $template): self
     {
         $this->template = $template;

         return $this;
     }




     /**
      * @param string $separator
      * @return $this
     */
     public function withSeparator(string $separator): self
     {
         $this->separator = $separator;

         return $this;
     }





     /**
      * @param string $title
      * @param string|null $link
      * @return $this
     */
     public function add(string $title, string $link = null): self
     {
          $this->items[$title] = $link;

          return $this;
     }



     /**
      * @param array $items
      * @return void
     */
     public function addItems(array $items): self
     {
         foreach ($items as $title => $link) {
             $this->add($title, $link);
         }

         return $this;
     }



     /**
      * @param array $items
      * @return $this
     */
     public function merge(array $items): self
     {
         $this->items = array_merge($this->items, $items);

         return $this;
     }




     /**
      * @return void
     */
     public function buildBreadcrumb()
     {
          $count = 0;
          $breadcrumb = '';

          foreach ($this->items as $title => $link) {

              $breadcrumb .= $this->view->render($this->template, compact('title', 'link'));
              $count++;

              if ($count !== count($this->items)) {
                  $breadcrumb .= $this->separator;
              }
          }

          echo $breadcrumb;
     }




     /**
      * @param array $items
      * @return string
     */
     public function renderBreadcrumb(array $items): string
     {
          extract($items, EXTR_SKIP);

          ob_start();
          @require $this->template;
          return ob_get_clean();
     }

}