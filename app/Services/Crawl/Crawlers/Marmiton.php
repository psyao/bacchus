<?php namespace Bacchus\Services\Crawl\Crawlers;

use Bacchus\Recipe;

class Marmiton extends Crawler
{
    /**
     * @inheritdoc
     */
    protected function name()
    {
        if (!isset($this->attributes['name'])) {
            $this->attributes['name'] = $this->text('h1.m_title span.item span.fn');
        }

        return $this->attributes['name'];
    }

    /**
     * @inheritdoc
     */
    protected function preparationTime()
    {
        if (!isset($this->attributes['preparation_time'])) {
            $this->attributes['preparation_time'] = $this->integer('p.m_content_recette_info span.preptime');
        }

        return $this->attributes['preparation_time'];
    }

    /**
     * @inheritdoc
     */
    protected function cookingTime()
    {
        if (!isset($this->attributes['cooking_time'])) {
            $this->attributes['cooking_time'] = $this->integer('p.m_content_recette_info span.cooktime');
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
        if (!isset($this->attributes['total_time'])) {
            $this->attributes['total_time'] = ($this->preparationTime() + $this->cookingTime());
        }

        return $this->attributes['total_time'];
    }

    /**
     * @inheritdoc
     */
    protected function guests()
    {
        if (!isset($this->attributes['guests'])) {
            $this->attributes['guests'] = $this->integer('p.m_content_recette_ingredients span');
        }

        return $this->attributes['guests'];
    }

    /**
     * @inheritdoc
     */
    protected function difficulty()
    {
        if (!isset($this->attributes['difficulty'])) {
            $this->extractDifficultyAndCost();
        }

        return $this->attributes['difficulty'];
    }

    /**
     * @inheritdoc
     */
    protected function price()
    {
        if (!isset($this->attributes['price'])) {
            $this->extractDifficultyAndCost();
        }

        return $this->attributes['price'];
    }

    /**
     * @inheritdoc
     */
    protected function ingredients()
    {
        if (!isset($this->attributes['ingredients'])) {
            $ingredients = [];

            $items = preg_split('/<br>|\n/', $this->crawler->filter('p.m_content_recette_ingredients')->html());

            foreach ($items as $index => &$ingredient) {
                if (starts_with($ingredient, '- ')) {
                    $ingredients[]['body'] = strip_tags(str_replace('- ', '', trim($ingredient)));
                }
            }

            $this->attributes['ingredients'] = $ingredients;
        }

        return $this->attributes['ingredients'];
    }

    /**
     * @inheritdoc
     */
    protected function steps()
    {
        if (!isset($this->attributes['steps'])) {
            $steps = [];

            $items = preg_split('/<br><br>/', $this->crawler->filter('div.m_content_recette_todo')->html());

            array_pop($items);

            foreach ($items as $index => &$step) {
                if ($index == 0) {
                    $step = explode("\n", $step);
                    $step = array_pop($step);
                }

                if (!empty($step)) {
                    $steps[]['body'] = strip_tags(trim($step));
                }
            }

            $this->attributes['steps'] = $steps;
        }

        return $this->attributes['steps'];
    }

    /**
     *
     */
    protected function extractDifficultyAndCost()
    {
        if (!isset($this->attributes['difficulty']) || !isset($this->attributes['price'])) {
            list($category, $difficulty, $price) = explode(' - ', $this->text('div.m_content_recette_breadcrumb'));

            switch ($difficulty) {
                case 'Très facile':
                case 'Facile':
                    $this->attributes['difficulty'] = Recipe::difficulties('easy');
                    break;
                case 'Difficile':
                    $this->attributes['difficulty'] = Recipe::difficulties('hard');
                    break;
                case 'Moyennement difficile':
                default:
                    $this->attributes['difficulty'] = Recipe::difficulties('medium');
            }

            switch ($price) {
                case 'Bon marché':
                    $this->attributes['price'] = Recipe::prices('low');
                    break;
                case 'Assez cher':
                    $this->attributes['price'] = Recipe::prices('high');
                    break;
                case 'Moyen':
                default:
                    $this->attributes['price'] = Recipe::prices('medium');
            }
        }
    }
}
