<?php
use Laventure\Component\Config\Config;
use Laventure\Component\Container\Container;
use Laventure\Component\Http\Response\RedirectResponse;
use Laventure\Component\Http\Response\Response;
use Laventure\Component\Routing\Router;
use Laventure\Component\Templating\Asset;


if(! function_exists('app')) {

    /**
     * @param string|null $abstract
     * @param array $parameters
     * @return Container
     * @throws
     */
    function app(string $abstract = null, array $parameters = []): Container
    {
        $app = Container::getInstance();

        if(is_null($abstract)) {
            return $app;
        }

        return $app->make($abstract, $parameters);
    }
}



if(! function_exists('base_path')) {

    /**
     * Base Path
     * @param string $path
     * @return string
     * @throws
     * @throws Exception
     */
    function base_path(string $path = ''): string
    {
        return app()->get('path') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}



if (! function_exists('url')) {

    function url($path, $params = []): string
    {
        return "";
    }
}


if(! function_exists('config')) {

    /**
     * Config
     * @param string $key
     * @return mixed
     * @throws
     * @throws Exception
     */
    function config(string $key = '')
    {
        /**
         * Config
         * @param string $key
         * @return mixed
         * @throws
         * @throws Exception
        */
        function config(string $key = '')
        {
            return app()->get(Config::class)->get($key);
        }
    }
}



# get environment variables
if(! function_exists('env'))
{
    /**
     * Get item from environment or default value
     *
     * @param $key
     * @param null $default
     * @return array|string|null
     */
    function env($key, $default = null)
    {
        $value = getenv($key);

        if(! $value) {
            return $default;
        }

        return $value;
    }
}



# get name of application
if(! function_exists('app_name')) {

    /**
     * Application name
     * @return string
     * @throws
     */
    function app_name(): string
    {
        return \config('app.name');
    }
}


# generate route path
if(! function_exists('route')) {

    /**
     * @param string $name
     * @param array $params
     * @return string
     * @throws
     * @throws Exception
     */
    function route(string $name, array $params = []): string
    {
        return \app()->get(Router::class)->generate($name, $params);
    }
}



# create a response
if(! function_exists('response'))
{

    /**
     * @param string $content
     * @param int $code
     * @param array $headers
     * @return Response
     */
    function response(string $content = '', int $code = 200, array $headers = []): Response
    {
        return new Response($content, $code, $headers);
    }
}



# redirect response
if(! function_exists('redirect'))
{

    /**
     * @param string $path
     * @param int $code
     * @return RedirectResponse
    */
    function redirect(string $path, int $code = 301): RedirectResponse
    {
        return new RedirectResponse($path, $code);
    }
}



# render a view
if(! function_exists('view'))
{

    /**
     * @param string $name
     * @param array $data
     * @return Response
     * @throws
     */
    function view(string $name, array $data = []): Response
    {
        $template = app()->get('view')->render($name, $data);

        return new Response($template, 200);
    }
}



# generate assets
if(! function_exists('assets'))
{

    /**
     * @param string|null $path
     * @return string
     * @throws
     * @throws Exception
     */
    function assets(string $path): string
    {
        /** @var Asset $asset */
        $asset = app()[Asset::class];

        $ext = pathinfo($path, PATHINFO_EXTENSION);

        switch ($ext) {
            case 'css':
                $link = $asset->renderCss($path);
                break;
            case 'js':
                $link = $asset->renderJs($path);
                break;
            default:
                $link = $asset->generatePath($path);
        }

        /*
        if (! file_exists($link)) {
            return '';
        }
        */

        return $link;
    }
}



if(! function_exists('styles')) {
    /**
     * @throws Exception
     * @return string
     */
    function styles(): string
    {
        // return \assets()->renderCss();
    }
}



if(! function_exists('scripts')) {
    /**
     * @throws Exception
     * @return string
     */
    function scripts(): string
    {
        // return \asset()->renderJs();
    }
}