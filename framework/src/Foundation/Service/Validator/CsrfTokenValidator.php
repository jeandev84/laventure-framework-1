<?php
namespace Laventure\Foundation\Service\Validator;

use Laventure\Component\Http\Session\Contract\SessionInterface;
use Laventure\Component\Http\Session\Session;



/**
 * @CsrfTokenValidator
*/
class CsrfTokenValidator
{

      /**
       * @var string
      */
      protected $tokenKey = '_csrf';



      /**
       * @var string
      */
      protected $hash;




      /**
       * @var Session
      */
      protected $session;




      /**
       * @param SessionInterface $session
       * @param string|null $hash
      */
      public function __construct(SessionInterface $session, string $hash = null)
      {
           $this->session = $session;

           if (! $hash) {
               $hash = md5(uniqid());
           }

           $this->withHash($hash);
      }



      /**
       * @param string $hash
       * @return $this
      */
      public function withHash(string $hash): self
      {
          $this->hash = $hash;

          $this->session->set($this->tokenKey, $hash);

          return $this;
      }




      /**
        * @return string
      */
      public function getHash(): string
      {
          return $this->hash;
      }




      /**
       * Take value from form
       * Example : <input type="hidden" name="_token" value="tokenHash">
       *  $token = $_POST['_token'] ?? null;
       *  if($this->isValidToken($token)) {}
       *
       * @param string $token
       * @return bool
      */
      public function isValidToken(string $token): bool
      {
           if ($this->hasToken() && $this->match($token)) {
                $this->forgetToken();
                return true;
           }

           return false;
      }




      /**
       * @param string $token
       * @return bool
      */
      public function match(string $token): bool
      {
           return $token === $this->session->get($this->tokenKey);
      }





      /**
       * @return bool|mixed
      */
      public function hasToken()
      {
          return $this->session->has($this->tokenKey);
      }




      /**
       * @return void
      */
      public function forgetToken()
      {
          $this->session->remove($this->tokenKey);
      }
}