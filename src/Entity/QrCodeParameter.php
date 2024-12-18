<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class QrCodeParameter
{

    #[Assert\Length(max: 2048)]
    public string $data = 'https://yaqrgen.com/';

    public string $format = 'png';

    #[Assert\Regex(pattern: '/^#[0-9a-fA-F]{6}$/')]
    public string $color  = '#000000';
    #[Assert\Regex(pattern: '/^#[0-9a-fA-F]{6}$/')]
    public ?string $color1 = null;
    #[Assert\Regex(pattern: '/^#[0-9a-fA-F]{6}$/')]
    public ?string $color2 = null;
    #[Assert\Regex(pattern: '/^#[0-9a-fA-F]{6}$/')]
    public ?string $color3 = null;
    #[Assert\Regex(pattern: '/^#[0-9a-fA-F]{6}$/')]
    public ?string $color4 = null;

    #[Assert\Regex(pattern: '/^#[0-9a-fA-F]{6}$/')]
    public string $bgColor = '#FFFFFF';

    public bool $transparent = false;

    #[Assert\Choice(choices: ['L', 'M', 'Q', 'H'])]
    public string $eccLevel = 'M';

    #[Assert\Url(requireTld: false)]
    public ?string $logoUrl = null;

    public ?int $logoSpaceWidth = 10;
    public ?int $logoSpaceHeight = 10;

    public ?int $scale = 10;

    public bool $drawCircularModules = false;
    public bool $keepAsSquare = false;
    #[Assert\Range(min: 1, max: 100)]
    public int $circleRadius = 50;

    public function __toArray(): array
    {
        return get_object_vars($this);
    }

}
