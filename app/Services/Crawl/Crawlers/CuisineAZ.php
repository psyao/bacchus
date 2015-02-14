<?php namespace Bacchus\Services\Crawl\Crawlers;

use Bacchus\Recipe;

class CuisineAZ extends Crawler
{
    /**
     * @inheritdoc
     */
    protected function name()
    {
        if ( !isset($this->attributes['name']))
        {
            $this->attributes['name'] = $this->string('h1.recetteH1');
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
            $this->attributes['preparation_time'] = $this->integer('span#ctl00_ContentPlaceHolder_LblRecetteTempsPrepa');
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
            $this->attributes['cooking_time'] = $this->integer('span#ctl00_ContentPlaceHolder_LblRecetteTempsCuisson');
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
            $this->attributes['guests'] = $this->integer('span#ctl00_ContentPlaceHolder_LblRecetteNombre');
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
            // The value is always a string
            $this->attributes['level'] = $this->evaluateLevelIndex($this->crawler->filter('td.cazicon-difficult')->text());
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
            // The value can be (str) 'Pas cher' or (str) '23,49' converted to (str) '23.49
            $value = $this->crawler->filter('td.cazicon-coutFR')->text();

            // The comma, if present, is converted to a dot
            $value = str_replace(',', '.', $value);

            $this->attributes['cost'] = is_numeric($value)
                ? $this->evaluateNumericCostIndex(intval($value) / $this->guests())
                : $this->evaluateStringCostIndex($value);
        }

        return $this->attributes['cost'];
    }

    /**
     * @param int $value
     *
     * @return int
     */
    protected function evaluateNumericCostIndex($value)
    {
        if ($value <= 4)
        {
            // Less or equals to 4€ per guest
            return Recipe::costs('low');
        }
        elseif ($value <= 6)
        {
            // More than 4€ but less than 6€ per guest
            return Recipe::costs('medium');
        }

        // More than 6€
        return Recipe::costs('high');
    }

    /**
     * @param string $value
     *
     * @return int
     */
    protected function evaluateStringCostIndex($value)
    {
        switch ($value)
        {
            case 'Pas cher':
                $index = Recipe::costs('low');
                break;
            case 'Assez cher':
                $index = Recipe::costs('high');
                break;
            case 'Abordable':
            default:
                $index = Recipe::costs('medium');
        }

        return $index;
    }

    /**
     * @param string $value
     *
     * @return mixed
     */
    protected function evaluateLevelIndex($value)
    {
        switch ($value)
        {
            case 'Facile':
                $level = Recipe::levels('easy');
                break;
            case 'Difficile':
                $level = Recipe::levels('hard');
                break;
            case 'Intermédiaire':
            default:
                $level = Recipe::levels('medium');
        }

        return $level;
    }
}
