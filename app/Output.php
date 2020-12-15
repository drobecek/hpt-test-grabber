<?php


namespace App;


class Output implements IOutput
{
    private $data;


    public function setData($data): void
    {
        $this->data = $data;
    }

    public function getJson(): string
    {
        return json_encode($this->data);
    }
}