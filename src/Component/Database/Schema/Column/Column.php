<?php
namespace Laventure\Component\Database\Schema\Column;



/**
 * @Column
*/
class Column
{

      /**
       * @var array
      */
      public $params = [
          'name'           => '',
          'type'           => '',
          'default'        => '',
          'primaryKey'     => '',
          'nullable'       => '',
          'collation'      => '',
          'comments'       => ''
      ];




      /**
        * @param array $params
      */
      public function __construct(array $params)
      {
          $this->params = array_merge($this->params, $params);
      }




      /**
       * @param $key
       * @param $value
       * @return $this
      */
      public function withParam($key, $value): Column
      {
          $this->params[$key] = $value;

          return $this;
      }




      /**
       * @param $key
       * @param $default
       * @return mixed|null
      */
      public function getParam($key, $default = null)
      {
           return $this->params[$key] ?? $default;
      }




      /**
       * Set nullable column
       *
       * @return self
      */
      public function nullable(): Column
      {
          return $this->withParam('nullable', 'DEFAULT NULL');
      }




      /**
       * add interphases
       * If $this->collation('utf8_unicode'),
       *
       * @param string $collation
       * @return Column
      */
      public function collation(string $collation): Column
      {
         return $this->withParam('collation', $collation);
      }




      /**
       * @param $comment
       * @return $this
      */
      public function comments($comment): Column
      {
          return $this->withParam('comments', $this->resolveComment($comment));
      }


      /**
       * @return array
      */
      public function getParamValues(): array
      {
          return array_values($this->params);
      }




     /**
      * @param $comment
      * @return string
     */
     protected function resolveComment($comment): string
     {
           return (string) (is_array($comment) ? join(', ', $comment) : $comment);
     }

}