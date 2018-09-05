<?php namespace Bacchus;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    /**
     * @var array
     */
    protected static $difficulties = [
        'easy'   => 0,
        'medium' => 1,
        'hard'   => 2
    ];
    /**
     * @var array
     */
    protected static $prices = [
        'low'    => 0,
        'medium' => 1,
        'high'   => 2
    ];
    /**
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'preparation_time', 'cooking_time', 'rest_time', 'total_time', 'difficulty', 'price', 'guests', 'url'
    ];

    /**
     * @param string|null $key
     *
     * @return mixed
     */
    public static function difficulties($key = null)
    {
        if (!is_null($key)) {
            return array_get(self::$difficulties, $key, false);
        }

        return self::$difficulties;
    }

    /**
     * @param string|null $key
     *
     * @return mixed
     */
    public static function prices($key = null)
    {
        if (!is_null($key)) {
            return array_get(self::$prices, $key, false);
        }

        return self::$prices;
    }

    /**
     * @param array $attributes
     *
     * @return static
     */
    public static function create(array $attributes)
    {
        $created = parent::create($attributes);

        if ($created && isset($attributes['ingredients'])) {
            $created->persistIngredients($attributes['ingredients'])
                    ->persistSteps($attributes['steps']);
        }

        return $created;
    }

    /**
     * @param $url
     *
     * @return \Bacchus\Recipe
     */
    public function import($url)
    {
        $attributes = app('crawler.factory')->make($url)->getAttributes();

        return static::create($attributes);
    }

    /**
     * @param array $attributes
     *
     * @return bool|int
     */
    public function update(array $attributes = [])
    {
        $updated = parent::update($attributes);

        if ($updated && isset($attributes['ingredients'])) {
            $updated->persistIngredients($attributes['ingredients'])
                    ->persistSteps($attributes['steps']);
        }

        return $updated;
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ingredients()
    {
        return $this->hasMany('Bacchus\Ingredient');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function steps()
    {
        return $this->hasMany('Bacchus\Step');
    }

    /**
     * @param array $ingredients
     *
     * @return $this
     */
    protected function persistIngredients(array $ingredients)
    {
        if ($this->exists) {
            foreach ($this->ingredients as $ingredient) {
                isset($ingredients[$ingredient->id])
                    ? $ingredient->update(array_pull($ingredients, $ingredient->id))
                    : $ingredient->delete();
            }
        }

        foreach ($ingredients as $attributes) {
            if (!empty($attributes['body'])) {
                $this->ingredients()->save(new Ingredient($attributes));
            }
        }

        return $this;
    }

    /**
     * @param array $steps
     *
     * @return $this
     */
    protected function persistSteps(array $steps)
    {
        if ($this->exists) {
            foreach ($this->steps as $step) {
                isset($steps[$step->id])
                    ? $step->update(array_pull($steps, $step->id))
                    : $step->delete();
            }
        }

        foreach ($steps as $attributes) {
            if (!empty($attributes['body'])) {
                $this->steps()->save(new Step($attributes));
            }
        }

        return $this;
    }

    /**
     *
     */
    protected function slugify()
    {
        if (!isset($this->attributes['slug']) || empty($this->attributes['slug'])) {
            $slug     = str_slug($this->attributes['name']);
            $lastSlug = static::whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'")->orderBy('slug', 'desc')->first();

            if (isset($lastSlug->slug)) {
                $slug = "{$slug}-" . ((intval(str_replace("{$slug}-", '', $lastSlug->slug))) + 1);
            }

            $this->attributes['slug'] = $slug;
        }
    }
}
