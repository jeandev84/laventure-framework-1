<?php
namespace Laventure\Component\Http\Request;


use Laventure\Component\Http\Bag\InputBag;
use Laventure\Component\Http\Bag\RequestHeaderBag;
use Laventure\Component\Http\Message\RequestInterface;
use Laventure\Component\Http\Message\StreamInterface;
use Laventure\Component\Http\Message\UriInterface;
use Laventure\Component\Http\Session\Session;
use Laventure\Component\Http\Utils\Parser\URLParser;


/**
 * @Request
 *
 * @package Laventure\Component\Http\Request
*/
class Request extends ServerRequestFactory implements RequestInterface
{

        /**
         * @var RequestHeaderBag
        */
        protected $headers;



        /**
         * @var string
        */
        protected $version;




        /**
         * @var string
        */
        protected $method;




        /**
          * @var string
        */
        protected $requestTarget;




        /**
         * @var Uri
        */
        public $uri;




        /**
         * @var string
        */
        public $content;




        /**
         * @var
        */
        public $parser;




        /**
         * @var Session
        */
        protected $sessions;




        /**
         * @param array $queries
         * @param array $request
         * @param array $attributes
         * @param array $cookies
         * @param array $files
         * @param array $server
         * @param string|null $content
        */
       public function __construct(
           array $queries = [],
           array $request = [],
           array $attributes = [],
           array $cookies = [],
           array $files = [],
           array $server = [],
           string $content = null
       )
       {
           parent::__construct($queries, $request, $attributes, $cookies, $files, $server);

           $this->sessions      = new Session();
           $this->headers       = new RequestHeaderBag();
           $this->parser        = new URLParser();
           $this->uri           = new Uri();
           $this->content       = $content;
           $this->requestTarget = null;
       }





        /**
         * @return $this
        */
        public static function createFromGlobals(): self
        {
             $request = static::createFromFactory($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER, 'php://input');

             if($request->hasContentTypeFormUrlEncoded() && $request->methodIn(['PUT', 'DELETE', 'PATCH'])) {
                  parse_str($request->getContent(), $data);
                  $request->request = new InputBag($data);
             }

             return $request;
        }




        /**
         * @param array $queries
         * @param array $request
         * @param array $attributes
         * @param array $cookies
         * @param array $files
         * @param array $server
         * @param string|null $content
         * @return Request
        */
        public static function createFromFactory(
            array $queries = [],
            array $request = [],
            array $attributes = [],
            array $cookies = [],
            array $files = [],
            array $server = [],
            string $content = null
        ): ServerRequestFactory
        {
            return new static($queries, $request, $attributes, $cookies, $files, $server, $content);
        }




       /**
        * @param array $attributes
        * @return void
       */
       public function setAttributes(array $attributes)
       {
            $this->attributes->merge($attributes);
       }





       /**
         * @inheritDoc
       */
       public function getProtocolVersion(): string
       {
           if ($this->version) {
               return $this->version;
           }

           return $this->version = $this->server->getProtocol();
       }




        /**
         * @inheritDoc
        */
        public function withProtocolVersion($version)
        {
            $this->version = $version;

            return $this;
        }



         /**
          * @inheritDoc
         */
         public function getHeaders(): array
         {
             return $this->headers->all();
         }



        /**
         * @inheritDoc
        */
        public function hasHeader($name): bool
        {
            return $this->headers->has($name);
        }




        /**
         * @inheritDoc
        */
        public function getHeader($name)
        {
             return $this->headers->get($name);
        }



        /**
         * @inheritDoc
         */
        public function getHeaderLine($name)
        {
            // TODO: Implement getHeaderLine() method.
        }

        /**
         * @inheritDoc
         */
        public function withHeader($name, $value)
        {
            // TODO: Implement withHeader() method.
        }

        /**
         * @inheritDoc
         */
        public function withAddedHeader($name, $value)
        {
            // TODO: Implement withAddedHeader() method.
        }

        /**
         * @inheritDoc
         */
        public function withoutHeader($name)
        {
            // TODO: Implement withoutHeader() method.
        }

        /**
         * @inheritDoc
         */
        public function getBody()
        {
            // TODO: Implement getBody() method.
        }

        /**
         * @inheritDoc
         */
        public function withBody(StreamInterface $body)
        {
            // TODO: Implement withBody() method.
        }



        /**
         * @inheritDoc
        */
        public function getRequestTarget()
        {
             return $this->requestTarget;
        }



        /**
         * @param $requestTarget
         * @return $this
        */
        public function withRequestTarget($requestTarget): Request
        {
              $this->requestTarget = $requestTarget;

              $this->uri = new Uri($requestTarget);

              return $this;
        }



        /**
          * @return string
        */
        public function getBaseURL(): string
        {
            return $this->server->getBaseURL();
        }




        /**
          * @return string
        */
        public function getURL(): string
        {
            return $this->server->getBaseURL(true);
        }




        /**
         * @inheritDoc
        */
        public function getMethod(): string
        {
            return $this->method ?? $this->server->get('REQUEST_METHOD', 'GET');
        }




        /**
         * @inheritDoc
        */
        public function withMethod($method)
        {
            $this->method = $method;

            $this->server->set('REQUEST_METHOD', $method);

            return $this;
        }




        /**
         * @inheritDoc
        */
        public function getUri(): UriInterface
        {
            if ($this->uri) {
                return $this->uri;
            }

            $userInfo = $this->server->getUserInfo();
            $password  = $this->server->getUserPass();

            $this->uri->withScheme($this->getScheme())
                      ->withHost($this->server->getHost())
                      ->withUserInfo($userInfo, $password)
                      ->withPort($this->server->getPort())
                      ->withPath($this->server->getPathInfo())
                      ->withQuery($this->server->getQueryString());

            // todo set fragment via JS

            return $this->uri;
        }



        /**
         * @inheritDoc
        */
        public function withUri(UriInterface $uri, $preserveHost = false)
        {
             $this->uri = $uri;

             return $this;
        }




        /**
          * @return mixed|null
        */
        public function getRequestUri()
        {
            return $this->server->get('REQUEST_URI');
        }





        /**
         * Determine if the protocol is secure
         *
         * @return bool
        */
        public function isSecure(): bool
        {
            return $this->server->isSecure();
        }




        /**
         * @param string $type
         * @return bool
        */
        public function isMethod(string $type): bool
        {
            return $this->getMethod() === strtoupper($type);
        }




        /**
         * @return bool
        */
        public function isGET(): bool
        {
            return $this->isMethod('GET');
        }




        /**
         * @return bool
        */
        public function isPOST(): bool
        {
            return $this->isMethod('POST');
        }




        /**
         * @return bool
        */
        public function isPUT(): bool
        {
            return $this->isMethod('POST');
        }




        /**
         * @return bool
        */
        public function isDELETE(): bool
        {
            return $this->isMethod('DELETE');
        }




        /**
         * @return string
        */
        public function getScheme(): string
        {
            return $this->server->getScheme();
        }



        /**
         * @return bool
        */
        public function isXhr(): bool
        {
            return $this->server->isXhr();
        }



        /**
         * @param string $host
         * @return bool
        */
        public function isValidHost(string $host): bool
        {
            return $this->server->getHost() === $host;
        }


        /**
         * @return mixed|null
        */
        public function getContentType()
        {
            return $this->headers->get('CONTENT_TYPE', '');
        }



        /**
         * @return bool
        */
        protected function hasContentTypeFormUrlEncoded(): bool
        {
            return stripos($this->getContentType(), 'application/x-www-form-urlencoded') === 0;
        }




        /**
         * @param array $methods
         * @return bool
        */
        protected function methodIn(array $methods): bool
        {
            return \in_array($this->toUpperMethod(), $methods);
        }




        /**
         * @return string
        */
        protected function toUpperMethod(): string
        {
            return strtoupper($this->getMethod());
        }

}