<?php namespace Bacchus\Services\Crawl\Crawlers;

class MarmitonCrawler extends Crawler
{
    /**
     * @var int
     */
    protected $level;
    /**
     * @var int
     */
    protected $cost;

    /**
     * @inheritdoc
     */
    protected function name()
    {
        return $this->string('h1.m_title span.item span.fn');
    }

    /**
     * @inheritdoc
     */
    protected function preparationTime()
    {
        return $this->integer('p.m_content_recette_info span.preptime');
    }

    /**
     * @inheritdoc
     */
    protected function cookingTime()
    {
        return $this->integer('p.m_content_recette_info span.cooktime');
    }

    /**
     * @inheritdoc
     */
    protected function totalTime()
    {
        return $this->attributes['preparation_time'] + $this->attributes['cooking_time'];
    }

    /**
     * @inheritdoc
     */
    protected function level()
    {
        $this->extractLevelAndCost();

        return $this->level;
    }

    /**
     * @inheritdoc
     */
    protected function cost()
    {
        $this->extractLevelAndCost();

        return $this->cost;
    }

    /**
     * @inheritdoc
     */
    protected function guests()
    {
        return $this->integer('p.m_content_recette_ingredients span');
    }

    /**
     *
     */
    protected function extractLevelAndCost()
    {
        if ( is_null($this->level) || is_null($this->cost))
        {
            list($category, $level, $cost) = explode(' - ', $this->string('div.m_content_recette_breadcrumb'));

            $this->level = $this->levels()[$level];
            $this->cost  = $this->costs()[$cost];
        }
    }

    /**
     * @return array
     */
    protected function levels()
    {
        return [
            'Trés facile'           => 0,
            'Facile'                => 0,
            'Moyennement difficile' => 1,
            'Difficile'             => 2,
        ];
    }

    /**
     * @return array
     */
    protected function costs()
    {
        return [
            'Bon marché' => 0,
            'Moyen'      => 1,
            'Assez cher' => 2,
        ];
    }
}
