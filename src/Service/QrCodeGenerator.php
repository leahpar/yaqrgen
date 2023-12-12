<?php

namespace App\Service;

use App\Entity\QrCodeParameter;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QrCodeGenerator
{

    public function generate(QrCodeParameter $qrCodeParameter): string
    {
        $options = $this->getOptions($qrCodeParameter);
        $qrcode = new QRCode(new QROptions($options));
        return $qrcode->render($qrCodeParameter->data);
    }

    private function getOptions(QrCodeParameter $qrCodeParameter): array
    {
        return $options = [
            //'version' => 10, // https://www.qrcode.com/en/about/version.html
            'versionMin' => 2,
            'versionMax' => 10,
            'eccLevel' => EccLevel::L,
            //'outputType' => QRCode::OUTPUT_MARKUP_SVG,
            'imageTransparent' => true,
        ];

    }

}
