<?php

namespace App\Service;

use App\Entity\QrCodeParameter;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QrCodeGenerator
{

    public function generate(QrCodeParameter $qrCodeParameter, string $format): string
    {
        $options = $this->getOptions($qrCodeParameter, $format);
        $qrcode = new QRCode(new QROptions($options));
        return $qrcode->render($qrCodeParameter->data);
    }

    private function getOptions(QrCodeParameter $qrCodeParameter, string $format): array
    {
        if ($format === 'svg') {
            return $options = [
                //'version' => 10, // https://www.qrcode.com/en/about/version.html
                'versionMin' => 2,
                'versionMax' => 10,
                'eccLevel' => EccLevel::L,
                'outputType' => QROutputInterface::MARKUP_SVG,
                'drawCircularModules' => true,
                'svgUseFillAttributes' => true,
            ];
        }
        else {
            return $options = [
                //'version' => 10,
                'versionMin' => 2,
                'versionMax' => 10,
                'eccLevel' => EccLevel::L,
                'outputType' => QROutputInterface::IMAGICK,
                'imagickFormat' => $format,
                'imageTransparent' => true,
            ];
        }

    }

}
