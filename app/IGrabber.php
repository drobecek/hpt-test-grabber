<?php
namespace App;

interface IGrabber
{

	/**
	 * @param string $productId
	 * @return float
	 */
	public function getPrice(string $productId): ?float;

}
