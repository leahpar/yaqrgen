<?php

namespace App\QrCodeGenerator;

use chillerlan\QRCode\QRCode;

class QrCodeReader
{

    public function read(string $url): ?string
    {
        try{
            dump($url);
            // https://php-qrcode.readthedocs.io/en/main/Usage/Quickstart.html#reading-qr-codes
            $result = (new QRCode)->readFromFile($url); // -> DecoderResult
            return $result->data;
        }
        catch(\Exception $e) {
            dump($e);
            return null;
        }
    }

}
