<?php

namespace App\QrCodeGenerator;

use App\Entity\QrCodeParameter;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QROutputAbstract;
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QrCodeGenerator
{

    public function generate(QrCodeParameter $qrCodeParameter, string $format): string
    {
        $options = $this->getOptions($qrCodeParameter, $format);
        $qroptions = new QROptions($options);
        $qrcode = new QRCode($qroptions);
        $qrcode->addByteSegment($qrCodeParameter->data);
        $matrix = $qrcode->getQRMatrix();

        if ($qrCodeParameter->logoUrl) {
            $qrOutputInterface = $this->getOutputInterfaceForLogo($qroptions, $matrix, $format);
            $imageData = $qrOutputInterface->dump(null, $qrCodeParameter->logoUrl);
            return sprintf('data:image/%s;base64,%s', $format, base64_encode($imageData));
        }
        else {
            return $qrcode->renderMatrix($matrix);
        }

    }

    private function getOutputInterfaceForLogo($qroptions, $matrix, string $format): QROutputAbstract
    {
        if ($format != 'png') throw new \Exception("Seulement le PNG est pris en compte pour l'instant pour les logos");
        return new QRImageWithLogo($qroptions, $matrix);
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
                //'drawCircularModules' => true,
                'svgUseFillAttributes' => true,
            ];
        }
        else {
            return $options = [
                //'version' => 10,
                'versionMin' => 2,
                'versionMax' => 10,
                'eccLevel' => $qrCodeParameter->logoUrl ? EccLevel::H : EccLevel::L,
                'outputType' => QROutputInterface::IMAGICK,
                'imagickFormat' => $format,

                'addLogoSpace'        => !empty($qrCodeParameter->logoUrl),
                'logoSpaceWidth'      => 10,
                'logoSpaceHeight'     => 10,
                'scale'               => 20,

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

//    private function downloadLogo(string $url): string
//    {
//        $filename = $this->logoUploadDir.'/'.microtime(true).'.img';
//
//        $response = $this->client->request('GET', $url);
//        if ($response->getStatusCode() !== 200) {
//            throw new \Exception("Logo not found");
//        }
//
//        $contentType = $response->getHeaders()['content-type'][0];
//        if (!str_starts_with($contentType, 'image/')) {
//            throw new \Exception("Logo must be an image");
//        }
//
//        // ...
//
//        return $filename;
//    }

}
