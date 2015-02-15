<?php namespace Bacchus\Services\Crawl\Crawlers;

use Bacchus\Recipe;

class SevenHundredAndFiftyGrams extends Crawler
{
    /**
     * @inheritdoc
     */
    protected function name()
    {
        if ( !isset($this->attributes['name']))
        {
            $this->attributes['name'] = $this->text('section.recette_titre h1.fn');
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
            $this->attributes['preparation_time'] = $this->integer('section.recette_infos span.preptime');
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
            $this->attributes['cooking_time'] = $this->integer('section.recette_infos span.cooktime');
        }

        return $this->attributes['cooking_time'];
    }

    /**
     * @inheritdoc
     */
    protected function restTime()
    {
        return null;
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
            $this->attributes['guests'] = $this->integer('section.recette_infos span.yield');
        }

        return $this->attributes['guests'];
    }

    /**
     * @inheritdoc
     */
    protected function difficulty()
    {
        if ( !isset($this->attributes['difficulty']))
        {
            switch ($this->text('section.recette_infos > div:nth-child(2) > p:nth-child(3) > span:nth-child(2)'))
            {
                case 'très facile':
                case 'facile':
                $this->attributes['difficulty'] = Recipe::difficulties('easy');
                    break;
                case 'difficile':
                case 'très difficile':
                    $$this->attributes['difficulty'] = Recipe::difficulties('hard');
                    break;
                case 'moyen':
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
        if ( !isset($this->attributes['price']))
        {
            $classes = explode(' ', $this->crawler->filter('section.recette_infos span.recette_cout > span')->attr('class'));

            switch (end($classes))
            {
                case 'cost_0':
                    $this->attributes['price'] = Recipe::prices('low');
                    break;
                case 'cost_2':
                case 'cost_3':
                    $this->attributes['price'] = Recipe::prices('high');
                    break;
                case 'cost_1':
                default:
                    $this->attributes['price'] = Recipe::prices('medium');
            }
        }

        return $this->attributes['price'];
    }
}
