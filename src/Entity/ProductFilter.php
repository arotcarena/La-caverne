<?php

namespace App\Entity;

use App\Entity\Brand;
use App\Entity\Category;



class ProductFilter
{
    /**
     * @var Brand|null
     */
    public $brand;

    /**
     * @var Category|null
     */
    public $category;

    /**
     * @var string|null
     */
    public $qSearch;

    /**
     * @var string|null
     */
    public $price_order;

    /**
     * @var int|null
     */
    public $price_max;

    /**
     * @var int|null
     */
    public $price_min;

    

    public function getQSearch(): ?string
    {
        return $this->qSearch;
    }

    public function setQSearch(?string $qSearch): self
    {
        $this->qSearch = $qSearch;

        return $this;
    }

    

    /**
     * Get the value of price_min
     *
     * @return  int|null
     */ 
    public function getPrice_min()
    {
        return $this->price_min;
    }

    /**
     * Set the value of price_min
     *
     * @param  int|null  $price_min
     *
     * @return  self
     */ 
    public function setPrice_min($price_min)
    {
        $this->price_min = $price_min;

        return $this;
    }

    /**
     * Get the value of price_max
     *
     * @return  int|null
     */ 
    public function getPrice_max()
    {
        return $this->price_max;
    }

    /**
     * Set the value of price_max
     *
     * @param  int|null  $price_max
     *
     * @return  self
     */ 
    public function setPrice_max($price_max)
    {
        $this->price_max = $price_max;

        return $this;
    }

    /**
     * Get the value of price_order
     *
     * @return  string|null
     */ 
    public function getPrice_order()
    {
        return $this->price_order;
    }

    /**
     * Set the value of price_order
     *
     * @param  string|null  $price_order
     *
     * @return  self
     */ 
    public function setPrice_order($price_order)
    {
        $this->price_order = in_array($price_order, ['asc', 'desc']) ? $price_order: null;

        return $this;
    }

    /**
     * Get the value of brand_id
     *
     * @return  Brand|null
     */ 
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set the value of brand_id
     *
     * @param  Brand|null  $brand_id
     *
     * @return  self
     */ 
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get the value of category_id
     *
     * @return  Category|null
     */ 
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the value of category_id
     *
     * @param  int|null  $category_id
     *
     * @return  self
     */ 
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }
}
