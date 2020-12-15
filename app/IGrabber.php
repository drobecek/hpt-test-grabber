<?php
namespace App;

interface IGrabber
{

	/**
	 * @param string $productId
	 * @return float
	 */
	public function getPrice(string $productId): ?float;

    /**
     * @param string $productId
     * @return Product|null
     */
	public function getProduct(string $productId): ?Product;

}
