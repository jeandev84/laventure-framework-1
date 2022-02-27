<?php
namespace Laventure\Component\Console\Input;



/**
 * @ConsoleInputArg
*/
class ConsoleInputArg extends InputArgv
{

         /**
          * @param array $tokens
         */
         public function __construct(array $tokens = [])
         {
               if (! $tokens) {
                   $tokens = $_SERVER['argv'];
               }

               parent::__construct($tokens);
         }



         /**
          * @inheritDoc
         */
         public function parseTokens($parses)
         {
              foreach ($parses as $token) {
                   $this->processParseToken($token);
              }
         }



         protected function logParameters()
         {
             return [
                'commands'  => $this->commands,
                'args'      => $this->arguments,
                'options'   => $this->options,
                'shortcuts' => $this->shortcuts,
                'flags'     => $this->flags
             ];
         }





         /**
           * @param $token
           * @return void
         */
         protected function processParseToken($token)
         {
             preg_match("/^(.+)=(.+)$/", $token, $matches);

             if (! empty($matches)) {

                 $tokenPrefixed = $matches[1];
                 $tokenValue    = $matches[2];
                 $params = str_split($tokenPrefixed);

                 if ($params[0] != '-') {
                     $this->withArgument($tokenPrefixed, $tokenValue);
                 }else{
                     if ($params[1] !== '-') {
                         unset($params[0]);
                         $name = implode($params);
                         $this->withShortcuts($name, $tokenValue);
                     }else{
                         unset($params[0], $params[1]);
                         $name = implode($params);
                         $this->withOption($name, $tokenValue);
                     }
                 }

             } else {

                 $params = str_split($token);
                 
                 if ($params[0] != '-') {
                     // case where example: php console arg0 arg1 arg2 ....
                      $this->withArgument($token);

                 }elseif ($params[1] == '-') {
                     // case where example: php console database:create ....
                     unset($params[0], $params[1]);
                     $name = implode($params);
                     $this->withFlag($name, true);
                 }else{
                     unset($params[0]);
                     $name = implode($params);
                     $this->withFlag($name, true);
                 }

             }
         }


        /**
          * @param $token
          * @return void
        */
        /*
        protected function processParseToken($token) {

            preg_match("/^(.+)=(.+)$/", $token, $matches);

             if(! empty($matches)) {

                  $tokenPrefixed = $matches[1];
                  $tokenValue    = $matches[2];
                  $params = str_split($tokenPrefixed);

                  if ($params[0] !== '-') {
                      $this->withArgument($matches[1], $matches[2]);
                  }else{
                      if ($params[1] !== '-') {
                          unset($params[0]);
                          $name = implode($params);
                          $this->withArgument($name, $tokenValue);
                      }else{
                          unset($params[0], $params[1]);
                          $name = implode($params);
                          $this->withOption($name, $tokenValue);
                      }
                  }

             }else {

                 $params = str_split($token);

                 if ($params[0] !== '-') {

                     $this->defaultArgument = $token;

                 }else {
                     if ($params[1] !== '-') {
                         unset($params[0]);
                         $name = implode($params);
                         $this->withArgumentFlag($name, true);
                     }else{
                         unset($params[0], $params[1]);
                         $name = implode($params);
                         $this->withOptionFlag($name, true);
                     }
                 }
             }

      }
     */

}