<?php
namespace Laventure\Component\Database\Builder\Common;



use Laventure\Component\Database\Builder\Exception\SqlBuilderException;



/**
 * @SqlBuilder
*/
abstract class SqlBuilder
{


      /**
       * @var string
      */
      protected $table = '';



      /**
       * @var string
      */
      protected $alias = '';




      /**
       * @var array
      */
      protected $where = [];





      /**
       * @var array
      */
      protected $sets = [];





      /**
       * @var array
      */
      protected $parameters = [];






      /**
       * @param string $table
       * @param string $alias
       * @return $this
      */
      public function table(string $table, string $alias = ''): self
      {
          $this->table = $alias ? sprintf('%s %s', $table, $alias) : $table;
          $this->alias = $alias;

          return $this;
      }




      /**
       * @param string $condition
       * @return $this
      */
      public function where(string $condition): self
      {
           return $this->andWhere($condition);
      }



      /**
       * @param string $condition
       * @return self
      */
      public function andWhere(string $condition): self
      {
           $this->where["AND"][] = $condition;

           return $this;
      }




      /**
       * @param string $condition
       * @return $this
      */
      public function orWhere(string $condition): self
      {
          $this->where["OR"][] = $condition;

          return $this;
      }




     /**
      * @param string $condition
      * @return self
     */
     public function notWhere(string $condition): self
     {
          return $this->andWhere("NOT $condition");
     }



     /**
      * @param string $pattern
      * @return $this
     */
     public function whereLike(string $pattern): self
     {
          return $this->andWhere("LIKE $pattern");
     }



     /**
      * @param $column
      * @param mixed $first
      * @param mixed $end
      * @return $this
     */
     public function whereBetween($column, $first, $end): self
     {
          return $this->andWhere("$column BETWEEN $first AND $end");
     }



    /**
     * @param mixed $first
     * @param mixed $end
     * @return $this
    */
    public function whereNotBetween($column, $first, $end): self
    {
        return $this->andWhere("$column NOT BETWEEN $first AND $end");
    }




    /**
     * @param $column
     * @param array $data
     * @return $this
    */
    public function whereIn($column, array $data): self
    {
        $printSQL = sprintf("%s IN (%s)", $column, implode(', ', $data));

        return $this->andWhere($printSQL);
    }



    /**
     * @param $column
     * @param array $data
     * @return $this
    */
    public function whereNotIn($column, array $data): self
    {
          $printSQL = sprintf("%s NOT IN (%s)", $column, implode(', ', $data));

          return $this->andWhere($printSQL);
    }




    /**
     * @param $key
     * @param $value
     * @return $this
    */
    public function set($key, $value): self
    {
        $this->sets[$key] = $value;

        return $this;
    }




    /**
     * @return Expr
    */
    public function expr(): Expr
    {
        return new Expr($this);
    }



     /**
       * @param $key
       * @param $value
       * @return $this
     */
     public function setParameter($key, $value): self
     {
           $this->parameters[$key] = $value;

           return $this;
     }




      /**
       * @param array $parameters
       * @return self
      */
      public function setParameters(array $parameters): self
      {
          foreach ($parameters as $key => $value) {
              $this->setParameter($key, $value);
          }

          /* $this->parameters = array_merge($this->parameters, $parameters); */

           return $this;
      }



      /**
       * @throws SqlBuilderException
      */
      protected function buildPrependSQL()
      {
          throw new SqlBuilderException(__METHOD__ . " must be implements.");
      }



      /**
       * @return string
      */
      protected function buildConditionSQL(): string
      {
          if (! empty($this->where)) {

               $sql  = ' WHERE ';
               $key = key($this->where);

               foreach ($this->where as $command => $conditions) {

                    $prefix = '';

                    if ($key !== $command) {
                       $prefix = $command;
                    }

                    $sql .= $this->buildConditions($command, $conditions, $prefix);
               }

               return $sql;

          }

          return '';
      }



      /**
       * @param string $command
       * @param array $conditions
       * @param string $prefix
       * @return string
      */
      private function buildConditions(string $command, array $conditions, string $prefix = ''): string
      {
          return ($prefix ? " ". $prefix . " " : ''  ) . $this->buildParts($command, $conditions);;
      }



      /**
       * @param string $command
       * @param array $conditions
       * @return string
      */
      private function buildParts(string $command, array $conditions): string
      {
          return implode( " ". $command . " ", $conditions);
      }



      /**
       * @return string
      */
      protected function buildComplementSQL(): string
      {
           return '';
      }



      /**
       * @return string
       * @throws SqlBuilderException
      */
      public function getSQL(): string
      {
           $sql = implode('', [
                   $this->buildPrependSQL(),
                   $this->buildConditionSQL(),
                   $this->buildComplementSQL()
           ]);

           return trim($sql, ' ');
      }



     /**
      * @return array
     */
     public function getParameters(): array
     {
        return $this->parameters;
     }
}