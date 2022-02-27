<?php
namespace Laventure\Component\Routing\Contract;


/**
 * @RouterInterface
 *
 * @package Laventure\Component\Routing\Contract
*/
interface RouterInterface
{


    /**
     * Determine or dispatch matched route and call method
     *
     * @param string $requestMethod
     * @param string $requestPath
     * @return mixed
    */
    public function match(string $requestMethod, string $requestPath);






    /**
     * Get matched or current route
     *
     * @return mixed
    */
    public function getRoute();





    /**
     * Get route collections
     *
     * @return mixed
    */
    public function getRoutes();




    /**
     * Generate link path
     *
     * Example: <a href="/admin/users">Users</a>
     *
     * @param string $name
     * @param array $parameters
     * @return mixed
    */
    public function generate(string $name, array $parameters = []);

}