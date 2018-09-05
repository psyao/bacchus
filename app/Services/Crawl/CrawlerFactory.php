<?php namespace Bacchus\Services\Crawl;

class CrawlerFactory
{
    /**
     * @param $url
     *
     * @return \Bacchus\Services\Crawl\Crawlers\Crawler
     */
    public function make($url)
    {
        $instance = array_first(config('recipes.crawlers'), function ($key, $value) use ($url) {
            return str_contains($url, $key);
        });

        return new $instance($url);
    }
}
