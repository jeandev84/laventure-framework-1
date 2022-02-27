<?php
namespace Laventure\Component\Http\Session;

use Laventure\Component\Http\Session\Contract\SessionInterface;


/**
 * @Session
*/
class Session implements SessionInterface
{


        /**
         * @var string
        */
        protected $flashKey = 'session.flash';




        /**
         * @param string $path
         * @param string $flashKey
        */
        public function __construct(string $flashKey = '', string $path = '')
        {
             if ($flashKey) {
                  $this->withFlashKey($flashKey);
             }

             if ($path) {
                 $this->saveTo($path);
             }


             $this->start();
        }




        /**
          * Start the session
          *
          * @return void
        */
        public function start()
        {
              if (session_status() === PHP_SESSION_NONE) {
                  session_start();
              }
        }





        /**
          * @param string $path
          * @return $this
        */
        public function saveTo(string $path): self
        {
               ini_set('session.save_path', $path);
               ini_set('session.gc_probability', 1);

               return $this;
        }




        /**
          * @return string
        */
        public function savePath(): string
        {
             return session_save_path();
        }




        /**
          * @param string $name
          * @param $value
          * @return void
        */
        public function set(string $name, $value): self
        {
            $_SESSION[$name] = $value;

             return $this;
        }





        /**
         * @param array $sessions
         * @return void
        */
        public function add(array $sessions)
        {
             foreach ($sessions as $name => $value) {
                 $this->set($name, $value);
             }
        }




        /**
          * @param string $name
          * @return bool
        */
        public function has(string $name): bool
        {
            return isset($_SESSION[$name]);
        }



        /**
         * @param string $name
         * @param $default
         * @return mixed|null
        */
        public function get(string $name, $default = null)
        {
            return $_SESSION[$name] ?? $default;
        }




        /**
         * @param string $name
         * @return void
        */
        public function remove(string $name)
        {
            unset($_SESSION[$name]);
        }



        /**
          * Remove all sessions
          *
          * @return void
        */
        public function clear()
        {
            $_SESSION = [];
            session_destroy();
        }




        /**
         * @return array
        */
        public function all(): array
        {
            return $_SESSION;
        }




        /**
         * @param string $flashKey
         * @return $this
        */
        public function withFlashKey(string $flashKey): self
        {
            $this->flashKey = $flashKey;

            return $this;
        }




        /**
         * @param string $type
         * @param string $message
         * @return Session
        */
        public function addFlash(string $type, string $message): self
        {
            $_SESSION[$this->flashKey][$type][] = $message;

            return $this;
        }




        /**
          * @param string $type
          * @return array|mixed
        */
        public function getFlash(string $type)
        {
            return $_SESSION[$this->flashKey][$type] ?? [];
        }




        /**
         * @return array|mixed
        */
        public function getFlashes()
        {
            return $_SESSION[$this->flashKey] ?? [];
        }
}