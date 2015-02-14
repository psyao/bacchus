<?php namespace Bacchus;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    /**
     * @var array
     */
    protected static $levels = [
        'easy'   => 0,
        'medium' => 1,
        'hard'   => 2
    ];
    /**
     * @var array
     */
    protected static $costs = [
        'low'    => 0,
        'medium' => 1,
        'high'   => 2
    ];
    /**
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'preparation_time', 'cooking_time', 'total_time', 'level', 'cost', 'guests', 'url'
    ];

    /**
     * @param string|null $key
     *
     * @return mixed
     */
    public static function levels($key = null)
    {
        if ( !is_null($key))
        {
            return array_get(self::$levels, $key, false);
        }

        return self::$levels;
    }

    /**
     * @param string|null $key
     *
     * @return mixed
     */
    public static function costs($key = null)
    {
        if ( !is_null($key))
        {
            return array_get(self::$costs, $key, false);
        }

        return self::$costs;
    }

    /**
     * @param string $url
     *
     * @return static
     */
    public static function import($url)
    {
        $crawler = app('crawler.factory')->make($url);

        return new static($crawler->getAttributes());
    }

    /**
     * @param array $options
     *
     * @return bool
     */
    public function save(array $options = [])
    {
        $this->slugify();

        return parent::save($options);
    }

    /**
     *
     */
    protected function slugify()
    {
        if ( !isset($this->attributes['slug']) || empty($this->attributes['slug']))
        {
            $slug     = str_slug($this->attributes['name']);
            $lastSlug = static::whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'")->orderBy('slug', 'desc')->first();

            if (isset($lastSlug->slug))
            {
                $slug = "{$slug}-" . ((intval(str_replace("{$slug}-", '', $lastSlug->slug))) + 1);
            }

            $this->attributes['slug'] = $slug;
        }
    }
}
