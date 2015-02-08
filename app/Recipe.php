<?php namespace Bacchus;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'preparation_time', 'cooking_time', 'total_time', 'level', 'cost', 'guests', 'url'
    ];

    /**
     * @param string $url
     *
     * @return static
     */
    public static function import($url)
    {
        $model = static::buildFromUrl($url);

        $model->save();

        return $model;
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

    public static function buildFromUrl($url)
    {
        $crawler = app('crawler.factory')->make($url);

        return new static($crawler->getAttributes());
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
