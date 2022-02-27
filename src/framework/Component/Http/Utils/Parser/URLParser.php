<?php
namespace Laventure\Component\Http\Utils\Parser;



/**
 * @URLParser
*/
class URLParser implements URLParserInterface, URLParsedParameterInterface
{


     /**
      * @var string
     */
     protected $path;




     /**
      * @param string|null $path
     */
     public function __construct(string $path = null)
     {
          if ($path) {
              $this->path($path);
          }
     }




     /**
      * @param string $path
      * @return void
     */
     public function path(string $path)
     {
          $this->path = $path;
     }




     /**
      * @param int $type
      * @return array|false|int|string|null
     */
     public function parse(int $type)
     {
         return parse_url($this->path, $type);
     }




     /**
       * @return array|false|int|string|null
     */
     public function parses()
     {
         return parse_url($this->path);
     }



    /**
     * @inheritDoc
    */
    public function getScheme()
    {
        return $this->parse(PHP_URL_SCHEME);
    }



    /**
     * @inheritDoc
    */
    public function getPassword()
    {
        return $this->parse(PHP_URL_PASS);
    }



    /**
     * @inheritDoc
    */
    public function getUserInfo()
    {
        return $this->parse(PHP_URL_USER);
    }



    /**
     * @inheritDoc
    */
    public function getHost()
    {
        return $this->parse(PHP_URL_HOST);
    }




    /**
     * @inheritDoc
     */
    public function getPort()
    {
        return $this->parse(PHP_URL_PORT);
    }




    /**
     * @inheritDoc
    */
    public function getPath()
    {
        return $this->parse(PHP_URL_PATH);
    }



    /**
     * @inheritDoc
    */
    public function getQueryString()
    {
        return $this->parse(PHP_URL_QUERY);
    }



    /**
     * @inheritDoc
    */
    public function getFragment()
    {
        return $this->parse(PHP_URL_FRAGMENT);
    }
}