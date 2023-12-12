<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class QrCodeParameter
{

    #[Assert\Length(max: 2048)]
    public string $data = 'https://yaqrgen.com/';

    public string $format = 'png';

    public function __toArray(): array
    {
        return [
            'data' => $this->data,
            'format' => $this->format,
        ];
    }
}
