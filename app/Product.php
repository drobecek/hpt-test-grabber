<?php


namespace App;

class Product
{
    /** @var string $sku Code of product */
    private $sku;

    /** @var float $price amount */
    private $price = 0.0;

    /** @var int $rating star rating */
    private $rating = 0;

    /**
     * Product constructor.
     *
     * @param $sku
     */
    public function __construct($sku)
    {
        $this->sku = $sku;
    }

    /**
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     */
    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    public function getPriceAndRating(): \stdClass
    {
        $obj = new \stdClass();
        $obj->price = 0.0 === $this->price ? NULL : $this->price;
        $obj->rating = 0 === $this->rating ? NULL : $this->rating;
        return $obj;
    }
}