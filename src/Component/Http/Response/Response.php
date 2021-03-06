<?php
namespace Laventure\Component\Http\Response;


use Laventure\Component\Http\Bag\ResponseHeaderBag;
use Laventure\Component\Http\Client\Message;
use Laventure\Component\Http\Message\ResponseInterface;
use Laventure\Component\Http\Message\StatusCodeInterface;
use Laventure\Component\Http\Response\Common\StatusCode;



/**
 * @Response
*/
class Response extends Message implements ResponseInterface, StatusCodeInterface
{


       use StatusCode;


      /**
       * @var string
      */
      protected $content;




      /**
       * @var int
      */
      protected $statusCode;




      /**
        * @var string
      */
      protected $reasonPhrase = '';




      /**
       * @var ResponseHeaderBag
      */
      public $headers;




      /**
       * @var string
      */
      protected $version = 'HTTP/1.0';




      /**
       * @param string|null $content
       * @param int $statusCode
       * @param array $headers
      */
      public function __construct($content = null, $statusCode = 200, array $headers = [])
      {
           $this->content    = $content;
           $this->statusCode = (int) $statusCode;
           $this->headers    = new ResponseHeaderBag($headers);
      }



      /**
       * @return string|null
      */
      public function getContent(): ?string
      {
           return $this->content;
      }




      /**
       * @param $content
       * @return void
      */
      public function setContent($content)
      {
          $this->content = $content;
      }




      /**
       * @return int
      */
      public function getStatusCode(): int
      {
          return $this->statusCode;
      }




      /**
       * @param int $code
       * @return void
      */
      public function setStatusCode(int $code)
      {
          $this->statusCode = $code;
      }




      /**
        * @param $code
        * @param $reasonPhrase
        * @return $this|Response
      */
      public function withStatus($code, $reasonPhrase = '')
      {
          $this->statusCode   = $code;
          $this->reasonPhrase = $reasonPhrase;

          return $this;
      }




      /**
       * @return string
      */
      public function getReasonPhrase(): string
      {
           return $this->reasonPhrase;
      }




      /**
       * @return array
      */
      public function getHeaders(): array
      {
          return $this->headers->all();
      }




      /**
        * @param $key
        * @param $value
        * @return void
      */
      public function setHeader($key, $value)
      {
          $this->headers->set($key, $value);
      }




      /**
       * @param $name
       * @param $value
       * @return $this
      */
      public function withHeader($name, $value = null): self
      {
          $this->headers->parse($name, $value);

          return $this;
      }




      /**
        * @param $name
       * @return Response
      */
      public function withoutHeader($name): self
      {
           $this->headers->remove($name);

           return $this;
      }




      /**
       * @param $name
       * @param $value
       * @return self
      */
      public function withAddedHeader($name, $value): self
      {
          $this->headers->merge([$name => $value]);

          return $this;
      }



      /**
       * @param array $headers
       * @return void
      */
      public function setHeaders(array $headers)
      {
          $this->headers->merge($headers);
      }




      /**
        * @param string $version
        * @return void
      */
      public function setProtocolVersion(string $version)
      {
           $this->version = $version;
      }




      /**
       * @return void
      */
      public function sendHeaders()
      {
           foreach ($this->getHeaders() as $k => $v) {
               foreach ($this->getHeaders() as $key => $value) {
                   header(\is_numeric($key) ? $value : $key .' : ' . $value);
               }

               /* header(sprintf('%s : %s', $k, $v)); */
           }
      }



      /**
       * @return void
      */
      public function sendStatusMessage()
      {
          if($message = $this->getMessage($this->statusCode)) {
              $this->withHeader(sprintf('%s %s %s', $this->version, $this->statusCode, $message));
          } else {
              $this->sendStatusCodeResponse();
          }
      }


      /**
       * @return $this
      */
      public function sendStatusCodeResponse(): Response
      {
          http_response_code($this->statusCode);
          return $this;
      }



      /**
       * @return void
      */
      public function sendContent()
      {
           echo $this->content;
      }



      public function sendBody()
      {
           // send $this->body or $this->content by default
      }



      /**
       * @return self
      */
      public function send()
      {
          // $this->clearPreviousHeaders();

          if (headers_sent()) {
              return $this;
          }

          $this->sendStatusCodeResponse();
          $this->sendHeaders();
      }


      /**
        * @return string
      */
      public function __toString()
      {
          return (string) $this->getContent();
      }


      /**
        * @return void
      */
      protected function clearPreviousHeaders()
      {
           if (! headers_sent()) {
               header_remove();
           }
      }

}