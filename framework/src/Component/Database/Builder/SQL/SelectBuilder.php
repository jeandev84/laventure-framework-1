<?php
namespace Laventure\Component\Database\Builder\SQL;


use Laventure\Component\Database\Builder\Common\SqlBuilder;
use Laventure\Component\Database\Builder\Exception\SqlBuilderException;


/**
 * @SelectBuilder
*/
class SelectBuilder extends SqlBuilder
{

            /**
             * @var array|string[]
            */
            protected $selects = [];




            /**
            * @var array
            */
            protected $from = [];





            /**
            * @var array
            */
            protected $orderBy = [];




            /**
            * @var array
            */
            protected $joins = [];





            /**
            * @var array
            */
            protected $groupBy = [];





            /**
            * @var
            */
            protected $having = [];




           /**
            * @var array
           */
           protected $unions = [];





          /**
           * @var string
          */
          protected $maxResults;




          /**
           * @var string
          */
          protected $firstResult;



         /**
          * SelectBuilder constructor
          *
          * @param array $selects
         */
         public function __construct(array $selects = ["*"])
         {
              $this->selects = $selects;
         }





        /**
         * @param string $table
         * @param string $alias
         * @return $this
        */
        public function from(string $table, string $alias = ''): self
        {
           $this->from[] = $alias ? sprintf('%s %s', $table, $alias) : $table;

           $this->table = $table;
           $this->alias = $table;

           return $this;
        }





        /**
         * @param $selects
         * @return $this
        */
        public function addSelect($selects): self
        {
           $this->selects = array_merge($this->selects, (array) $selects);

           return $this;
        }




        /**
         * @param $field
         * @param string|null $direction
         * @return $this
        */
        public function orderBy($field, string $direction = null): self
        {
              $orderBy = $field;

              if ($direction) {
                  $orderBy = sprintf('%s %s', $field, strtoupper($direction));
              }

              $this->orderBy[] = $orderBy;

              return $this;
        }





        /**
         * @param $field
         * @return $this
        */
        public function orderByAsc($field): self
        {
             return $this->orderBy($field, 'ASC');
        }




        /**
         * @param $sort
         * @return $this
        */
        public function orderByDesc($sort): self
        {
             return $this->orderBy($sort, 'DESC');
        }






        /**
          * @return $this
        */
        public function addOrderBy($orderBy): self
        {
             return $this->orderBy($orderBy);
        }



        /**
         * @param $table
         * @param string $condition
         * @param string $type
         * @return $this
        */
        public function join($table, string $condition, string $type = 'INNER'): self
        {
            $this->joins[$type][] = sprintf('%s JOIN %s ON %s', $type, $table, $condition);

            return $this;
        }




        /**
         * @param $table
         * @param string $condition
         * @return $this
        */
        public function innerJoin($table, string $condition): self
        {
             return $this->join($table, $condition);
        }




        /**
         * @param $table
         * @param $condition
         * @return $this
        */
        public function leftJoin($table, $condition): self
        {
             return $this->join($table, $condition, 'LEFT');
        }




        /**
         * @param $table
         * @param $condition
         * @return self
        */
        public function rightJoin($table, $condition): self
        {
             return $this->join($table, $condition, 'RIGHT');
        }




        /**
         * @param $table
         * @param $condition
         * @return $this
        */
        public function fullJoin($table, $condition): self
        {
             return $this->join($table, $condition, 'FULL');
        }





        /**
         * @param $table
         * @param $condition
         * @return $this
        */
        public function fullOuterJoin($table, $condition): self
        {
             return $this->join($table, $condition, 'FULL OUTER');
        }





        /**
         * @param $column
         * @return $this
        */
        public function groupBy($column): self
        {
            $this->groupBy[] = sprintf('GROUP BY %s', $column);

            return $this;
        }



        /**
         * @param $column
         * @return $this
        */
        public function addGroupBy($column): self
        {
            return $this->groupBy($column);
        }


        /**
         * @param $condition
         * @return $this
        */
        public function having($condition): self
        {
            $this->having[] = sprintf('HAVING %s', $condition);

            return $this;
        }





        /**
         * @param $having
         * @return $this
        */
        public function andHaving($having): self
        {
             return $this;
        }





        /**
         * @param $having
         * @return $this
        */
        public function orHaving($having): self
        {
            return $this;
        }





        /**
          * @return void
          * @throws SqlBuilderException
        */
        public function union($select)
        {
              // todo implements
              $this->unions[] = sprintf('%s UNION %s', $this->getSQL(), $select);
        }



        /**
         * @return void
         * @throws SqlBuilderException
        */
        public function unionALL($select)
        {
             // todo implements
             $this->unions[] = sprintf('%s UNION ALL %s', $this->getSQL(), $select);
        }



        /**
        * @inheritDoc
        */
        protected function buildPrependSQL(): string
        {
              return sprintf("SELECT %s FROM %s %s",
                implode(', ', $this->selects),
                implode(',', $this->from),
                $this->buildJoins()
              );
        }



        /**
        * @return string
        */
        protected function buildComplementSQL(): string
        {
               $sql = ' ';

               if ($this->orderBy) {
                   $sql .= sprintf('ORDER BY %s', join(', ', $this->orderBy));
               }

               if ($this->groupBy) {
                  $sql .= join(' ', $this->groupBy);
               }


               if ($this->having) {
                   $sql .= join(' ', $this->having);
               }

               return $sql;
        }


        /**
          * @return string
        */
        protected function buildJoins(): string
        {
             $sql = '';

             if ($this->joins) {

                foreach ($this->joins as $type => $joinParams) {
                    $sql .= implode(' ', $joinParams) . ' ';
                }
            }

            return $sql;
        }

}