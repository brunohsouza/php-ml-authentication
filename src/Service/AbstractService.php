<?php


namespace App\Service;


class AbstractService
{

    public function removeComma(string $statement): string
    {
        return str_replace(',', '', $statement);
    }
}