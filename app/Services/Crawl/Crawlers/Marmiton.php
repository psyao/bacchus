<?php namespace Bacchus\Services\Crawl\Crawlers;

use Bacchus\Recipe;

class Marmiton extends Crawler
{
    /**
     * @inheritdoc
     */
    protected function name()
    {
        if ( !isset($this->attributes['name']))
        {
            $this->attributes['name'] = $this->string('h1.m_title span.item span.fn');
        }

        return $this->attributes['name'];
    }

    /**
     * @inheritdoc
     */
    protected function preparationTime()
    {
        if ( !isset($this->attributes['preparation_time']))
        {
            $this->attributes['preparation_time'] = $this->integer('p.m_content_recette_info span.preptime');
        }

        return $this->attributes['preparation_time'];
    }

    /**
     * @inheritdoc
     */
    protected function cookingTime()
    {
        if ( !isset($this->attributes['cooking_time']))
        {
            $this->attributes['cooking_time'] = $this->integer('p.m_content_recette_info span.cooktime');
        }

        return $this->attributes['cooking_time'];
    }

    /**
     * @inheritdoc
     */
    protected function totalTime()
    {
        if ( !isset($this->attributes['total_time']))
        {
            $this->attributes['total_time'] = ($this->preparationTime() + $this->cookingTime());
        }

        return $this->attributes['total_time'];
    }

    /**
     * @inheritdoc
     */
    protected function guests()
    {
        if ( !isset($this->attributes['guests']))
        {
            $this->attributes['guests'] = $this->integer('p.m_content_recette_ingredients span');
        }

        return $this->attributes['guests'];
    }

    /**
     * @inheritdoc
     */
    protected function level()
    {
        if ( !isset($this->attributes['level']))
        {
            $this->extractLevelAndCost();
        }

        return $this->attributes['level'];
    }

    /**
     * @inheritdoc
     */
    protected function cost()
    {
        if ( !isset($this->attributes['cost']))
        {
            $this->extractLevelAndCost();
        }

        return $this->attributes['cost'];
    }

    /**
     *
     */
    protected function extractLevelAndCost()
    {
        if ( !isset($this->attributes['level']) || !isset($this->attributes['cost']))
        {
            list($category, $level, $cost) = explode(' - ', $this->string('div.m_content_recette_breadcrumb'));

            switch ($level)
            {
                case 'Très facile':
                case 'Facile':
                    $this->attributes['level'] = Recipe::levels('easy');
                    break;
                case 'Difficile':
                    $this->attributes['level'] = Recipe::levels('hard');
                    break;
                case 'Moyennement difficile':
                default:
                    $this->attributes['level'] = Recipe::levels('medium');
            }

            switch ($cost)
            {
                case 'Bon marché':
                    $this->attributes['cost'] = Recipe::costs('low');
                    break;
                case 'Assez cher':
                    $this->attributes['cost'] = Recipe::costs('high');
                    break;
                case 'Moyen':
                default:
                    $this->attributes['cost'] = Recipe::costs('medium');
            }
        }
    }
}
