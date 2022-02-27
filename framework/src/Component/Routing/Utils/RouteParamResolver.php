<?php
namespace Laventure\Component\Routing\Utils;



/**
 * @RouteParamResolver
*/
class RouteParamResolver
{

     /**
      * @param $methods
      * @return array
     */
     public function resolveMethods($methods): array
     {
         if (\is_string($methods)) {
             $methods = explode('|', $methods);
         }

         return (array) $methods;
     }



     /**
      * @param $domain
      * @return string
     */
     public function resolveDomain($domain): string
     {
         return $this->removeTrailingSlashes($domain) ;
     }


     /**
      *
      * @param string $path
      * @param string|null $prefix
      * @return string
     */
     public function resolvePath(string $path, string $prefix = null): string
     {
         return $prefix ? trim($prefix, '/'). '/'. ltrim($path, '/') : $path;
     }




     public function resolveCallback($callback)
     {
          //todo implements
     }



    /**
     * @param $path
     * @return string
    */
    public function removeTrailingSlashes($path): string
    {
        return trim($path, '\\/');
    }



    /**
     * @param $path
     * @return string
    */
    public function trimmed($path): string
    {
        return $this->removeTrailingSlashes($path);
    }
}