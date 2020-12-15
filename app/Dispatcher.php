<?php
namespace App;

class Dispatcher
{
    private $items;

	/**
	 * @var IGrabber
	 */
	private $grabber;
	/**
	 * @var IOutput
	 */
	private $output;

	/**
	 * @param IGrabber $grabber
	 * @param IOutput $output
	 */
	public function __construct(IGrabber $grabber, IOutput $output)
	{
		$this->grabber = $grabber;
		$this->output = $output;
		$this->items = [];
	}

	public function loadInput($file)
    {
        $this->items = [];

        $handle = fopen($file, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $this->items[] = trim($line);
            }
            fclose($handle);
        }
    }

	/**
	 * @return string JSON
	 */
	public function run()
	{
        $prices = [];

	    foreach ( $this->items as $item) {
            if(!isset($prices[$item])) {
                $prices[$item] = [];
            }
            $prices[$item]['price'] = $this->grabber->getPrice($item);
        }

         $this->output->setData($prices);

        return $this->output->getJson();
	}
}
