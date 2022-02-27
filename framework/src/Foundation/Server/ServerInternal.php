<?php
namespace Laventure\Foundation\Server;


/**
 * @ServerInternal
*/
class ServerInternal
{

     /**
      * @var string
     */
     protected $scriptFilename;



     /**
      * ServerInternal constructor
      *
      * @param string $scriptFilename
     */
     public function __construct(string $scriptFilename)
     {
          $this->scriptFilename = $scriptFilename;
     }



     /**
       * @return false|mixed
     */
     public function run(string $refreshedTo = '')
     {
          $url = parse_url($this->getRequestUri(), PHP_URL_PATH);

          $directory = dirname($this->scriptFilename);

          if ($url !== '/' && file_exists($directory.$url)) {
              return false;
          }

          $this->refreshScriptName($refreshedTo);

          return require_once realpath($this->scriptFilename);
     }



     /**
      * @return mixed|string
     */
     private function getRequestUri()
     {
         return $_SERVER['REQUEST_URI'] ?? '/';
     }



     /**
      * @param string|null $scriptName
      * @return void
     */
     private function refreshScriptName(?string $scriptName)
     {
         (function () use ($scriptName) {
             $_SERVER['SCRIPT_NAME'] = $scriptName ?? '/index.php';
         })();
     }



     /**
      * @param string $scriptName
      * @return void
     */
     private function resolveScriptName(string $scriptName) {}
}