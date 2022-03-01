<?php
namespace Laventure\Component\Database\Schema\Column;



use ArrayAccess;



/**
 * @Column
*/
class Column implements ArrayAccess
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
      public function __construct(array $params = [])
      {
           if ($params) {
               $this->withParams($params);
           }
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
       * @param array $params
       * @return $this
      */
      public function withParams(array $params): self
      {
          $this->params = array_merge($this->params, $params);

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
       * @param $key
       * @return bool
      */
      public function hasParam($key): bool
      {
          return \array_key_exists($key, $this->params);
      }




     /**
      * @param $comment
      * @return string
     */
     protected function resolveComment($comment): string
     {
           return (is_array($comment) ? join(', ', $comment) : $comment);
     }




     /**
      * @inheritDoc
     */
     public function offsetExists($offset): bool
     {
          return $this->hasParam($offset);
     }



     /**
      * @inheritDoc
     */
     public function offsetGet($offset)
     {
         return $this->getParam($offset);
     }




     /**
      * @inheritDoc
     */
     public function offsetSet($offset, $value)
     {
          $this->withParam($offset, $value);
     }



     /**
      * @inheritDoc
     */
     public function offsetUnset($offset)
     {
          unset($this->params[$offset]);
     }
}