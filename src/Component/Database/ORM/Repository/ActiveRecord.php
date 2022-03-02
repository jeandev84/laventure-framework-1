<?php
namespace Laventure\Component\Database\ORM\Repository;


use Laventure\Component\Database\ORM\Contract\ActiveRecordInterface;
use Laventure\Component\Database\ORM\Repository\Common\ActiveRecordRepository;



/**
 * @ActiveRecord
*/
abstract class ActiveRecord implements ActiveRecordInterface
{
    use ActiveRecordRepository;
}