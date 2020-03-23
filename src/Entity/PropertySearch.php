<?php
//Entity non relié a la base de données, utiliser pour les filtres dans le catalogue

namespace App\Entity;

class PropertySearch{

    /**
     * @var int|null
     */
    private $maxPrice;

    /**
     * @var null
     */
    private $selectedCategory;

    /**
     * @var null
     */
    private $searchbar;

    /**
     * @return null
     */
    public function getOrderBy1()
    {
        return $this->orderBy1;
    }

    /**
     * @var null
     */
    private $orderBy1;

    /**
     * @param null $orderBy1
     * @return PropertySearch
     */
    public function setOrderBy1($orderBy1)
    {
        $this->orderBy1 = $orderBy1;
        return $this;
    }

    /**
     * @return null
     */
    public function getSearchbar()
    {
        return $this->searchbar;
    }

    /**
     * @param null $searchbar
     * @return PropertySearch
     */
    public function setSearchbar($searchbar)
    {
        $this->searchbar = $searchbar;
        return $this;
    }

    /**
     * @return null
     */
    public function getSelectedCategory()
    {
        return $this->selectedCategory;
    }

    /**
     * @param null $selectedCategory
     * @return PropertySearch
     */
    public function setSelectedCategory($selectedCategory)
    {
        $this->selectedCategory = $selectedCategory;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMaxPrice(): ?int
    {
        return $this->maxPrice;
    }

    /**
     * @param int|null $maxPrice
     * @return PropertySearch
     */
    public function setMaxPrice(int $maxPrice): PropertySearch
    {
        $this->maxPrice = $maxPrice;
        return $this;
    }

}