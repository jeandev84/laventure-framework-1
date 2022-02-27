<?php
namespace Laventure\Component\Http\Request;


use Laventure\Component\Http\Message\UriInterface;
use Laventure\Component\Http\Utils\Parser\URLParser;


/**
 * @Uri
*/
class Uri implements UriInterface
{


    /**
     * Get scheme
     *
     * @var string
    */
    protected $scheme;




    /**
     * Get host
     *
     * @var string
    */
    protected $host;





    /**
     * Get username
     *
     * @var string
    */
    protected $username;




    /**
     * Get password
     *
     * @var string
    */
    protected $password;







    /**
     * Get port
     *
     * @var string
    */
    protected $port;





    /**
     * Get path
     *
     * @var string
    */
    protected $path;





    /**
     * Query string
     *
     * @var string
    */
    protected $queryString;





    /**
     * Fragment request
     *
     * @var string
    */
    protected $fragment;



    /**
     * @var URLParser
    */
    protected $parser;



    /**
     * @param string $targetPath
    */
    public function __construct(string $targetPath = '')
    {
         $this->parser = new URLParser($targetPath);
    }




    /**
     * @inheritDoc
    */
    public function getScheme()
    {
        return $this->scheme ?? $this->parser->getScheme();
    }



    /**
     * @inheritDoc
    */
    public function getAuthority()
    {
        return $this->password ?? $this->parser->getPassword();
    }



    /**
     * @inheritDoc
    */
    public function getUserInfo()
    {
        return $this->username ?? $this->parser->getUserInfo();
    }





    /**
     * @inheritDoc
    */
    public function getHost()
    {
        return $this->host ?? $this->parser->getHost();
    }




    /**
     * @inheritDoc
    */
    public function getPort()
    {
        return $this->port ?? $this->parser->getPort();
    }




    /**
     * @inheritDoc
     */
    public function getPath()
    {
        return $this->path ?? $this->parser->getPath();
    }



    /**
     * @inheritDoc
    */
    public function getQuery()
    {
        return $this->queryString ?? $this->parser->getQueryString();
    }



    /**
     * @inheritDoc
    */
    public function getFragment()
    {
        return $this->fragment ?? $this->parser->getFragment();
    }




    /**
     * @inheritDoc
    */
    public function withScheme($scheme)
    {
         $this->scheme = $scheme;

         return $this;
    }



    /**
     * @inheritDoc
    */
    public function withUserInfo($user, $password = null)
    {
         $this->username = $user;
         $this->password = $password;

         return $this;
    }




    /**
     * @inheritDoc
    */
    public function withHost($host)
    {
        $this->host = $host;

        return $this;
    }




    /**
     * @inheritDoc
    */
    public function withPort($port)
    {
         $this->port = $port;

         return $this;
    }




    /**
     * @inheritDoc
    */
    public function withPath($path)
    {
         $this->path = $path;

         return $this;
    }





    /**
     * @inheritDoc
    */
    public function withQuery($query)
    {
         $this->queryString = $query;
    }





    /**
     * @inheritDoc
     */
    public function withFragment($fragment)
    {
        $this->fragment = $fragment;

        return $this;
    }



    /**
     * @inheritDoc
    */
    public function __toString()
    {
        // TODO: Implement __toString() method.
    }




    public function getTarget()
    {

    }
}