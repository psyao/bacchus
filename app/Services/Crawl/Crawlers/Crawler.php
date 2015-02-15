<?php namespace Bacchus\Services\Crawl\Crawlers;

use Goutte\Client;

abstract class Crawler
{
    /**
     * @var \Goutte\Client
     */
    protected $crawler;
    /**
     * @var array
     */
    protected $fillable = [
        'name', 'preparation_time', 'cooking_time', 'rest_time', 'total_time', 'guests', 'difficulty', 'price'
    ];
    /**
     * @var array
     */
    protected $attributes = [];
    /**
     * @var array
     */
    protected $lookup = [];

    /**
     * @param string $url
     */
    function __construct($url)
    {
        $this->crawler = (new Client())->request('GET', $url);

        $this->setAttributes($url);
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param string $url
     */
    protected function setAttributes($url)
    {
        foreach ($this->fillable as $attribute)
        {
            $method = camel_case($attribute);

            $this->{$method}();
        }

        $this->attributes['url'] = $url;
    }

    /**
     * @param string $selector
     *
     * @return string
     */
    protected function string($selector)
    {
        return $this->crawler->filter($selector)->text();
    }

    /**
     * @param string $selector
     *
     * @return int
     */
    protected function integer($selector)
    {
        if (ctype_digit($string = $this->string($selector)))
        {
            return intval($string);
        }

        $time = str_contains($string, 'h') ? 60 : 1;

        preg_match_all('/\d+/', $this->string($selector), $matches);

        return intval($matches[0][0]) * $time;
    }

    /**
     * @return string
     */
    abstract protected function name();

    /**
     * @return int
     */
    abstract protected function preparationTime();

    /**
     * @return int
     */
    abstract protected function cookingTime();

    /**
     * @return int
     */
    abstract protected function restTime();

    /**
     * @return int
     */
    abstract protected function totalTime();

    /**
     * @return int
     */
    abstract protected function guests();

    /**
     * @return int
     */
    abstract protected function difficulty();

    /**
     * @return int
     */
    abstract protected function price();
}
