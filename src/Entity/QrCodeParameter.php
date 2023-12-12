<?php

namespace App\Entity;

class QrCodeParameter
{

    public string $data = 'https://yaqrgen.com/';
    public string $format = 'svg';

    public function __toArray(): array
    {
        return [
            'data' => $this->data,
            'format' => $this->format,
        ];
    }
}
