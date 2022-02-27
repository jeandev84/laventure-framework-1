<?php
namespace Laventure\Component\Http\Session;


use Laventure\Component\Http\Session\Contract\FlashInterface;



/**
 * @Flash
*/
class Flash implements FlashInterface
{


    const SUCCESS  = 'success';
    const ERRORS   = 'errors';




    /**
     * @var Session
    */
    protected $session;




    /**
     * @param Session $session
    */
    public function __construct(Session $session)
    {
         $this->session = $session;
    }





    /**
     * @param string $type
     * @param $message
     * @return $this;
    */
    public function add(string $type, $message): self
    {
          $this->session->addFlash($type, $message);

          return $this;
    }




    /**
     * @param string $name
     * @return array|mixed
    */
    public function get(string $name)
    {
        return $this->session->getFlash($name);
    }



    /**
     * @param $message
     * @return void
    */
    public function addSuccess($message)
    {
          $this->add(self::SUCCESS, $message);
    }




    /**
     * @return array|mixed
    */
    public function getSuccess()
    {
        return $this->get(self::SUCCESS);
    }




    /**
     * @param $message
     * @return void
    */
    public function addErrors($message)
    {
        $this->add(self::ERRORS, $message);
    }




    /**
     * @return array|mixed
     */
    public function getErrors()
    {
        return $this->get(self::ERRORS);
    }




    /**
     * @return array|mixed
    */
    public function getMessages()
    {
         return $this->session->getFlashes();
    }

}