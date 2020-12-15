<?php


namespace App;


class Output implements IOutput
{
    private $data = [];

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function getJson(): string
    {
        $return = new \stdClass();
        /** @var Product $item */
        foreach (  $this->data as $item)
        {
            $return->{$item->getSku()} = $item->getPriceAndRating();
        }

        return json_encode($return);
    }
}