<?php

namespace App\Service;

use App\Entity\QrCodeParameter;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Data\QRMatrix;
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
                'imageTransparent' => $qrCodeParameter->transparent,
                'bgColor' => $qrCodeParameter->bgColor,
                'moduleValues' => [
                    QRMatrix::M_FINDER_DARK    => $qrCodeParameter->color,
                    QRMatrix::M_FINDER_DOT     => $qrCodeParameter->color,
                    QRMatrix::M_FINDER         => $qrCodeParameter->bgColor,
                    QRMatrix::M_ALIGNMENT_DARK => $qrCodeParameter->color,
                    QRMatrix::M_ALIGNMENT      => $qrCodeParameter->bgColor,
                    QRMatrix::M_VERSION_DARK   => $qrCodeParameter->color,
                    QRMatrix::M_VERSION        => $qrCodeParameter->bgColor,
                    QRMatrix::M_TIMING_DARK    => $qrCodeParameter->color,
                    QRMatrix::M_TIMING         => $qrCodeParameter->bgColor,
                    QRMatrix::M_FORMAT_DARK    => $qrCodeParameter->color,
                    QRMatrix::M_FORMAT         => $qrCodeParameter->bgColor,
                    QRMatrix::M_DARKMODULE     => $qrCodeParameter->color,
                    QRMatrix::M_SEPARATOR      => $qrCodeParameter->bgColor,
                    QRMatrix::M_DATA_DARK      => $qrCodeParameter->color,
                    QRMatrix::M_DATA           => $qrCodeParameter->bgColor,
                ],
            ];
        }

    }

}
