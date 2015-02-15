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
    protected function text($selector)
    {
        $node = $this->crawler->filter($selector);

        if ( !$node->count())
        {
            return null;
        }

        return strip_tags(str_replace("&nbsp;", " ", htmlentities($node->text(), null, 'utf-8')));
    }

    /**
     * @param string $selector
     * @param bool   $strict
     *
     * @return int|string
     */
    protected function integer($selector, $strict = true)
    {
        $value = $this->text($selector);

        if (is_null($value))
        {
            var_dump('null');

            return 0;
        }

        // The string does not contain number.
        if ( !preg_match('/\d+/', $value))
        {
            if ($strict)
            {
                throw new \BadMethodCallException("The selector does not contain a number.");
            }
            else
            {
                return $value;
            }
        }

        // The string is an integer. Example: "1337".
        if (ctype_digit($value))
        {
            return intval($value);
        }

        // The string contains hours. Example: "3 heures" returns 180.
        if (preg_match('/(\d+) *h[A-z]* *(\d+)?/i', $value, $hours))
        {
            $hour    = intval($hours[1]);
            $minutes = isset($hours[2]) ? intval($hours[2]) : 0;

            return $hour * 60 + $minutes;
        }

        // The string contains seconds. Example: "30 secondes" returns 30.
        if (preg_match('/(\d+)[ ]*s/i', $value, $seconds))
        {
            return max(intval($seconds[1]) / 60, 1);
        }

        // The string contains a least a number. Example: "15.6$" returns 15 or "€ 34,99" returns 34.
        if (preg_match('/(\d*[.|,]?\d+)/', $value, $numbers))
        {
            return intval($numbers[1]);
        }

        return intval($value);
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
