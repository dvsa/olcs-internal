<?php

namespace Olcs\Data\Object\Search;

/**
 * Class SearchAbstract
 * @package Olcs\Data\Object\Search
 */
abstract class SearchAbstract
{
    /**
     * @var
     */
    protected $title;
    /**
     * @var
     */
    protected $key;

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return array
     */
    public function getVariables()
    {
        return ['title' => $this->getTitle()];
    }

    /**
     * @return array
     */
    public function getSettings()
    {
        return [
            'paginate' => [
                'limit' => [
                    'options' => [10, 25, 50]
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getTableConfig()
    {
        return [
            'variables' => $this->getVariables(),
            'settings' => $this->getSettings(),
            'attributes' => $this->getAttributes(),
            'columns' => $this->getColumns()
        ];
    }

    /**
     * @return array
     */
    public function getNavigation()
    {
        return ['label' => $this->getTitle(), 'route' => 'search', 'params' => ['index' => $this->getKey()]];
    }
}
