<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class QrCodeParameter
{

    #[Assert\Length(max: 2048)]
    public string $data = 'https://yaqrgen.com/';

    public string $format = 'png';

    #[Assert\Regex(pattern: '/^#[0-9a-fA-F]{6}$/')]
    public string $color = '#000000';

    #[Assert\Regex(pattern: '/^#[0-9a-fA-F]{6}$/')]
    public string $bgColor = '#FFFFFF';

    public bool $transparent = true;

    #[Assert\Url]
    public ?string $logoUrl = null;

    public function __toArray(): array
    {
        return get_object_vars($this);
    }

}
