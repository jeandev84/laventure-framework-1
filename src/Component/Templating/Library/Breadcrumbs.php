<?php

namespace Laventure\Component\Templating\Library;


/*
https://coderius.biz.ua/blog/article/hlebnye-kroski-na-php
https://getbootstrap.com/docs/4.5/components/breadcrumb/
*/
class Breadcrumbs
{
    private $breadcrumb;

    private $config = [];

    private function __construct($config)
    {
        $defaultConfig = ['domain' => $this->getBaseUrl(), 'separator' => '/'];
        $this->config = array_merge($defaultConfig, $config);
    }

    public static function init($config = [])
    {
        return new self($config);
    }

    private function getBaseUrl()
    {
        return (isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'];
    }

    private function renderItem($url, $title)
    {
        return "<a href=\"$url\"><span>$title</span></a>";
    }

    public function build($array)
    {
        $breadcrumbs = [];

        $count = 0;

        foreach ($array as $title => $url) {
            ++$count;

            $breadcrumbs[] = $count !== count($array) ? $this->renderItem($this->config['domain'].'/'.$url, $title) : "<span>$title</span";
        }

        return implode($this->config['separator'], $breadcrumbs);
    }

    public function buildFromQuery($texts = [])
    {
        $path = array_filter(explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));

        $count = 0;
        $crumbs = '';

        foreach ($path as $crumb) {
            ++$count;
            $title = isset($texts[$crumb]) ? $texts[$crumb] : ucwords(str_replace(['.php', '_', '%20'], ['', ' ', ' '], $crumb));
            $url = $this->config['domain'].'/'.$crumbs.$crumb;
            $breadcrumbs[] = $count !== count($path) ? $this->renderItem($url, $title) : "<span>$title</span";
            $crumbs .= $crumb.'/';
        }

        return implode($this->config['separator'], $breadcrumbs);
    }
}

/*
Example 1:

echo Breadcrumbs::init(['domain' => 'https://gist.github.com'])->build([
        'Home' => '',
        'Category' => 'category/php-breadcrumb.html',
        'Subcategory' => 'category/php-breadcrumb.html/subcatewgory/lalala',
        'Post' => 'post/post-php-breadcrumb.html',
    ]);

<a href="https://gist.github.com/">
<span>Home</span>
</a>
/
<a href="https://gist.github.com/category/php-breadcrumb.html">
<span>Category</span>
</a>
/
<a href="https://gist.github.com/category/php-breadcrumb.html/subcatewgory/lalala"><span>Subcategory</span>
</a>
/
<span>Post</span>


Example 2:

echo Breadcrumbs::init(['domain' => 'https://coderius.biz.ua'])->buildFromQuery([
        'blog-articles' => 'Страницы блога',
    ]);

Frontend/Web/Страницы блога/Create

echo Breadcrumbs::init(['domain' => 'https://gist.github.com','separator' => '>>'])->buildFromQuery([
        'category' => 'Категория',
    ]);
*/