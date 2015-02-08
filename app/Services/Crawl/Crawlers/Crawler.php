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
        'name', 'preparation_time', 'cooking_time', 'total_time', 'level', 'cost', 'guests'
    ];
    /**
     * @var array
     */
    protected $attributes = [];

    function __construct($url)
    {
        $this->crawler = (new Client())->request('GET', $url);

        foreach ($this->fillable as $attribute)
        {
            $method = camel_case($attribute);

            if (method_exists($this, $method))
            {
                $this->attributes[$attribute] = $this->{$method}();
            }
        }

        $this->attributes['url'] = $url;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param $selector
     *
     * @return string
     */
    protected function string($selector)
    {
        return $this->crawler->filter($selector)->text();
    }

    /**
     * @param $selector
     *
     * @return int
     */
    protected function integer($selector)
    {
        if (ctype_digit($string = $this->string($selector)))
        {
            return intval($string);
        }

        preg_match_all('/\d+/', $this->string($selector), $matches);

        return intval($matches[0][0]);
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
    abstract protected function totalTime();

    /**
     * @return int
     */
    abstract protected function level();

    /**
     * @return int
     */
    abstract protected function cost();

    /**
     * @return int
     */
    abstract protected function guests();
}
