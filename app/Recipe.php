<?php namespace Bacchus;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{

    protected $fillable = ['name', 'slug', 'preparation_time', 'cooking_time', 'total_time', 'level', 'cost', 'guests'];

    public function save(array $options = [])
    {
        $this->slugify();

        return parent::save($options);
    }

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
