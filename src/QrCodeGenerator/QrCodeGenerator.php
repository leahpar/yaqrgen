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
        if ($qrCodeParameter->logoUrl) {
            $format = 'png'; // TODO: support other formats
        }

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
        $eccLevel = match ($qrCodeParameter->eccLevel) {
            'L' => EccLevel::L,
            'M' => EccLevel::M,
            'Q' => EccLevel::Q,
            'H' => EccLevel::H,
        };

        $outputType = match ($format) {
            'svg' => QROutputInterface::MARKUP_SVG,
            default => QROutputInterface::IMAGICK,
        };

        $svgBgColor = $qrCodeParameter->transparent ? 'transparent' : $qrCodeParameter->bgColor;

        // Set default colors
        $qrCodeParameter->color1 ??= $qrCodeParameter->color;
        $qrCodeParameter->color2 ??= $qrCodeParameter->color;
        $qrCodeParameter->color3 ??= $qrCodeParameter->color;
        $qrCodeParameter->color4 ??= $qrCodeParameter->color;

        return $options = [
            //'version' => 10,
            'versionMin'          => 2,
            'versionMax'          => 20,
            'eccLevel'            => $qrCodeParameter->logoUrl ? EccLevel::H : $eccLevel,
            'outputType'          => $outputType,
            'imagickFormat'       => $format,

            'drawCircularModules' => $qrCodeParameter->drawCircularModules,
            'circleRadius'        => $qrCodeParameter->circleRadius / 100,
            'connectPaths'        => true,
            'keepAsSquare'        => $qrCodeParameter->keepAsSquare ? [
                    QRMatrix::M_FINDER_DARK,
                    QRMatrix::M_FINDER_DOT,
                    QRMatrix::M_ALIGNMENT_DARK,
                ] : [],

            'addLogoSpace'        => !empty($qrCodeParameter->logoUrl),
            'logoSpaceWidth'      => $qrCodeParameter->logoSpaceWidth,
            'logoSpaceHeight'     => $qrCodeParameter->logoSpaceHeight,

            'scale'               => $qrCodeParameter->scale,

            'bgColor'             => $qrCodeParameter->bgColor,
            'imageTransparent'    => $qrCodeParameter->transparent,
            'drawLightModules'    => false,

            'moduleValues' => [
                QRMatrix::M_FINDER_DARK    => $qrCodeParameter->color1,
                QRMatrix::M_FINDER_DOT     => $qrCodeParameter->color2,
                QRMatrix::M_FINDER         => $qrCodeParameter->bgColor,
                QRMatrix::M_ALIGNMENT_DARK => $qrCodeParameter->color3,
                QRMatrix::M_ALIGNMENT      => $qrCodeParameter->bgColor,
                QRMatrix::M_VERSION_DARK   => $qrCodeParameter->color4,
                QRMatrix::M_VERSION        => $qrCodeParameter->bgColor,
                QRMatrix::M_TIMING_DARK    => $qrCodeParameter->color4,
                QRMatrix::M_TIMING         => $qrCodeParameter->bgColor,
                QRMatrix::M_FORMAT_DARK    => $qrCodeParameter->color4,
                QRMatrix::M_FORMAT         => $qrCodeParameter->bgColor,
                QRMatrix::M_DARKMODULE     => $qrCodeParameter->color4,
                QRMatrix::M_SEPARATOR      => $qrCodeParameter->bgColor,
                QRMatrix::M_DATA_DARK      => $qrCodeParameter->color4,
                QRMatrix::M_DATA           => $qrCodeParameter->bgColor,
            ],

            'svgUseFillAttributes'  => false,
            'svgDefs'               => <<<SVG
                    <style><![CDATA[
                        .dark {fill: {$qrCodeParameter->color1};}
                        /*.light {fill: {$qrCodeParameter->bgColor};}*/
                        svg {background-color: {$svgBgColor};}
                    ]]></style>
                    SVG,
//
//            'svgDefs' => <<<SVG
//                    <linearGradient id="rainbow" x1="1" y2="1">
//                        <stop stop-color="#e2453c" offset="0"/>
//                        <stop stop-color="#e07e39" offset="0.2"/>
//                        <stop stop-color="#e5d667" offset="0.4"/>
//                        <stop stop-color="#51b95b" offset="0.6"/>
//                        <stop stop-color="#1e72b7" offset="0.8"/>
//                        <stop stop-color="#6f5ba7" offset="1"/>
//                    </linearGradient>
//                    <style><![CDATA[
//                        .dark{fill: url(#rainbow);}
//                        .light{fill: #eee;}
//                    ]]></style>
//                    SVG,
//
        ];

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
