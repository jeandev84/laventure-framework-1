<?php
namespace Laventure\Component\Console\Output\Helpers;


use Laventure\Component\Console\Output\Contract\OutputInterface;


/**
 *
 * https://www.php.net/manual/fr/function.printf.php
 * https://www.php.net/manual/fr/function.print.php
 * https://www.php.net/manual/fr/function.flush.php
 * https://www.php.net/manual/fr/features.commandline.io-streams.php
 * https://www.php.net/manual/fr/features.commandline.options.php
 *
 * @Table
*/
class Table
{


      /**
       * first is number, second is string text
       *
       * @var string
      */
      protected $mask = ""; // |%5.5s |%-30.30s | x |\n";





      /**
       * @var OutputInterface
      */
      protected $output;




      /**
       * @var array
      */
      protected $headers = [];




      /**
       * @var array
      */
      protected $rows = [];




      /**
       * @param OutputInterface $output
      */
      public function __construct(OutputInterface $output)
      {
           $this->output = $output;
      }




      /**
       * @param array $headers
       * @return $this
      */
      public function setHeaders(array $headers = []): self
      {
          $this->headers = array_merge($this->headers, $headers);

          return $this;
      }




      /**
       * @param array $rows
       * @return $this
      */
      public function addRow(array $rows): self
      {
           $this->rows  = array_merge($this->rows, $rows);

           return $this;
      }




      public function render()
      {

      }
}

/*
$mask = "%-30.30s %-30.30s  x |\n";
printf($mask, 'Num', 'Title');
printf($mask, 'eeeee', 'A value that fits the cell');
printf($mask, 'eeeee', 'A too long value the end of which will be cut off');


// fixed width
$mask = "|%5.5s |%-30.30s | x |\n";
printf($mask, 'Num', 'Title');
printf($mask, '1', 'A value that fits the cell');
printf($mask, '2', 'A too long value the end of which will be cut off');
The output is

|  Num |Title                          | x |
|    1 |A value that fits the cell     | x |
|    2 |A too long value the end of wh | x |
Second case:

// only min-width of cells is set
$mask = "|%5s |%-30s | x |\n";
printf($mask, 'Num', 'Title');
printf($mask, '1', 'A value that fits the cell');
printf($mask, '2', 'A too long value that will brake the table');
And here we get

|  Num |Title                          | x |
|    1 |A value that fits the cell     | x |
|    2 |A too long value that will brake the table | x |


require_once 'Console/Table.php';

$tbl = new Console_Table();

$tbl->setHeaders(array('Language', 'Year'));

$tbl->addRow(array('PHP', 1994));
$tbl->addRow(array('C',   1970));
$tbl->addRow(array('C++', 1983));

echo $tbl->getTable();


require_once 'Console/Table.php';

$tbl = new Console_Table();
$tbl->setHeaders(
    array('Language', 'Year')
);
$tbl->addRow(array('PHP', 1994));
$tbl->addRow(array('C',   1970));
$tbl->addRow(array('C++', 1983));

echo $tbl->getTable();
*/