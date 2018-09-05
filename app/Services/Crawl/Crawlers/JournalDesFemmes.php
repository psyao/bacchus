<?php namespace Bacchus\Services\Crawl\Crawlers;

use Bacchus\Recipe;

class JournalDesFemmes extends Crawler
{
    /**
     * @inheritdoc
     */
    protected function name()
    {
        if (!isset($this->attributes['name'])) {
            $this->attributes['name'] = $this->text('div.hrecipe h1.bu_cuisine_title_1 > span.fn');
        }

        return $this->attributes['name'];
    }

    /**
     * @inheritdoc
     */
    protected function preparationTime()
    {
        if (!isset($this->attributes['preparation_time'])) {
            $this->attributes['preparation_time'] = $this->integer('div.hrecipe ul.bu_cuisine_carnet_2 span.preptime');
        }

        return $this->attributes['preparation_time'];
    }

    /**
     * @inheritdoc
     */
    protected function cookingTime()
    {
        if (!isset($this->attributes['cooking_time'])) {
            $this->attributes['cooking_time'] = $this->integer('div.hrecipe ul.bu_cuisine_carnet_2 span.cooktime');
        }

        return $this->attributes['cooking_time'];
    }

    /**
     * @inheritdoc
     */
    protected function restTime()
    {
        if (!isset($this->attributes['rest_time'])) {
            $this->attributes['rest_time'] = $this->totalTime() - $this->preparationTime() - $this->cookingTime();
        }

        return $this->attributes['rest_time'];
    }

    /**
     * @inheritdoc
     */
    protected function totalTime()
    {
        if (!isset($this->attributes['total_time'])) {
            $this->attributes['total_time'] = $this->integer('div.hrecipe > article.bu_cuisine_main_recipe span.duration');
        }

        return $this->attributes['total_time'];
    }

    /**
     * @inheritdoc
     */
    protected function guests()
    {
        if (!isset($this->attributes['guests'])) {
            $this->attributes['guests'] = $this->integer('div.hrecipe p.bu_cuisine_title_3 > span.yield');
        }

        return $this->attributes['guests'];
    }

    /**
     * @inheritdoc
     */
    protected function difficulty()
    {
        if (!isset($this->attributes['difficulty'])) {
            switch ($this->text('div.hrecipe ul.bu_cuisine_carnet_2 li:nth-child(1)')) {
                case 'Facile':
                    $this->attributes['difficulty'] = Recipe::difficulties('easy');
                    break;
                case 'Très difficile':
                    $this->attributes['difficulty'] = Recipe::difficulties('hard');
                    break;
                case 'Difficile':
                default:
                    $this->attributes['difficulty'] = Recipe::difficulties('medium');
            }
        }

        return $this->attributes['difficulty'];
    }

    /**
     * @inheritdoc
     */
    protected function price()
    {
        return Recipe::prices('medium');
    }

    /**
     * @inheritdoc
     */
    protected function ingredients()
    {
        if (!isset($this->attributes['ingredients'])) {
            $ingredients = [];

            $nodes = $this->crawler->filter('div.hrecipe > article.bu_cuisine_main_recipe li.ingredient');

            $nodes->each(function ($node, $i) use (&$ingredients) {
                $ingredients[]['body'] = trim($node->text());
            });

            $this->attributes['ingredients'] = $ingredients;
        }

        return $this->attributes['ingredients'];
    }
}
