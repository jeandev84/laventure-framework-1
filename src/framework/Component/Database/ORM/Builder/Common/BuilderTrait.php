<?php
namespace Laventure\Component\Database\ORM\Builder\Common;


use Laventure\Component\Database\Builder\Exception\SqlBuilderException;
use Laventure\Component\Database\Connection\Exception\LogicException;
use Laventure\Component\Database\Connection\Exception\StatementException;
use Laventure\Component\Database\ORM\EntityManager;
use Laventure\Component\Database\ORM\Query\Query;


/**
 * @BuilderTrait
*/
trait BuilderTrait
{


    /**
     * @var EntityManager
    */
    protected $em;



    /**
     * @param EntityManager $em
     * @return void
    */
    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }




    /**
     * @return Query
    */
    public function getQuery(): Query
    {
        return (function () {
            $query = new Query($this->em);

            return $query->query(
                $this->getSQL(),
                $this->getParameters()
            );

        })();
    }




    /**
     * @return void
    */
    public function execute()
    {
        $this->getQuery()->execute();
    }


}