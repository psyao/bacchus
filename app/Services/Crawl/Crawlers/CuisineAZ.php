<?php namespace Bacchus\Services\Crawl\Crawlers;

use Bacchus\Recipe;

class CuisineAZ extends Crawler
{
    /**
     * @inheritdoc
     */
    protected function name()
    {
        if (!isset($this->attributes['name'])) {
            $this->attributes['name'] = $this->text('h1.recetteH1');
        }

        return $this->attributes['name'];
    }

    /**
     * @inheritdoc
     */
    protected function preparationTime()
    {
        if (!isset($this->attributes['preparation_time'])) {
            $this->attributes['preparation_time'] = $this->integer('#ctl00_ContentPlaceHolder_LblRecetteTempsPrepa');
        }

        return $this->attributes['preparation_time'];
    }

    /**
     * @inheritdoc
     */
    protected function cookingTime()
    {
        if (!isset($this->attributes['cooking_time'])) {
            $this->attributes['cooking_time'] = $this->integer('#ctl00_ContentPlaceHolder_LblRecetteTempsCuisson');
        }

        return $this->attributes['cooking_time'];
    }

    /**
     * @inheritdoc
     */
    protected function restTime()
    {
        if (!isset($this->attributes['rest_time'])) {
            $this->attributes['rest_time'] = $this->integer('#ctl00_ContentPlaceHolder_LblRecetteTempsRepos');
        }

        return $this->attributes['rest_time'];
    }

    /**
     * @inheritdoc
     */
    protected function totalTime()
    {
        if (!isset($this->attributes['total_time'])) {
            $this->attributes['total_time'] = ($this->preparationTime() + $this->cookingTime() + $this->restTime());
        }

        return $this->attributes['total_time'];
    }

    /**
     * @inheritdoc
     */
    protected function guests()
    {
        if (!isset($this->attributes['guests'])) {
            $this->attributes['guests'] = $this->integer('#ctl00_ContentPlaceHolder_LblRecetteNombre');
        }

        return $this->attributes['guests'];
    }

    /**
     * @inheritdoc
     */
    protected function difficulty()
    {
        if (!isset($this->attributes['difficulty'])) {
            // The value is always a string
            $this->attributes['difficulty'] = $this->getDifficultyIndex($this->text('td.cazicon-difficult'));
        }

        return $this->attributes['difficulty'];
    }

    /**
     * @inheritdoc
     */
    protected function price()
    {
        if (!isset($this->attributes['price'])) {
            $this->attributes['price'] = $this->getPriceIndex($this->integer('td.cazicon-coutFR', false));
        }

        return $this->attributes['price'];
    }

    /**
     * @return array
     */
    protected function ingredients()
    {
        if (!isset($this->attributes['ingredients'])) {
            $ingredients = [];

            $nodes = $this->crawler->filter('#ingredients > ul > li.ingredient > span');

            $nodes->each(function ($node, $i) use (&$ingredients) {
                $text = explode(' : ', trim($node->text()));
                $ingredients[]['body'] = strip_tags(trim(implode(' ', array_reverse($text))));
            });

            $this->attributes['ingredients'] = $ingredients;
        }

        return $this->attributes['ingredients'];
    }

    /**
     * @param int|string $value
     *
     * @return int
     */
    protected function getPriceIndex($value)
    {
        return is_int($value) ? $this->evaluateNumericPriceIndex($value) : $this->evaluateTextPriceIndex($value);
    }

    /**
     * @param int $value
     *
     * @return int
     */
    protected function evaluateNumericPriceIndex($value)
    {
        $value /= $this->guests();

        if ($value <= 6) {
            // Less or equals to 4€ per guest
            return Recipe::prices('low');
        } elseif ($value <= 9) {
            // More than 4€ but less than 6€ per guest
            return Recipe::prices('medium');
        }

        // More than 6€
        return Recipe::prices('high');
    }

    /**
     * @param string $value
     *
     * @return int
     */
    protected function evaluateTextPriceIndex($value)
    {
        switch ($value) {
            case 'Pas cher':
                $index = Recipe::prices('low');
                break;
            case 'Assez cher':
                $index = Recipe::prices('high');
                break;
            case 'Abordable':
            default:
                $index = Recipe::prices('medium');
        }

        return $index;
    }

    /**
     * @param string $value
     *
     * @return mixed
     */
    protected function getDifficultyIndex($value)
    {
        switch ($value) {
            case 'Facile':
                $difficulty = Recipe::difficulties('easy');
                break;
            case 'Difficile':
                $difficulty = Recipe::difficulties('hard');
                break;
            case 'Intermédiaire':
            default:
                $difficulty = Recipe::difficulties('medium');
        }

        return $difficulty;
    }
}
