<?php
namespace Laventure\Component\Http\Utils\Parser;


/**
 * @URLParsedParameterInterface
*/
interface URLParsedParameterInterface
{
    /**
     * @return mixed
     */
    public function getScheme();






    /**
     * @return mixed
     */
    public function getPassword();






    /**
     * @return mixed
     */
    public function getUserInfo();






    /**
     * @return mixed
     */
    public function getHost();






    /**
     * @return mixed
     */
    public function getPort();




    /**
     * @return mixed
     */
    public function getPath();




    /**
     * @return mixed
     */
    public function getQueryString();





    /**
     * @return mixed
    */
    public function getFragment();
}