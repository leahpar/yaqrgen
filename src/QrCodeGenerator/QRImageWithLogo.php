<?php
/**
 * GdImage with logo output example
 *
 * @created      18.11.2020
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2020 smiley
 * @license      MIT
 */

namespace App\QrCodeGenerator;

use chillerlan\QRCode\Output\QRGdImagePNG;
use chillerlan\QRCode\Output\QRCodeOutputException;

class QRImageWithLogo extends QRGdImagePNG
{

    public function dump(string $file = null, string $logo = null) :string
    {
        // Open the logo image
        // Throw an exception if the logo file is not readable
        $im = imagecreatefromstring(file_get_contents($logo));

        // set returnResource to true to skip further processing for now
        $this->options->returnResource = true;

        // there's no need to save the result of dump() into $this->image here
        parent::dump($file);

        // get logo image size
        $w = imagesx($im);
        $h = imagesy($im);

        // set new logo size, leave a border of 1 module (no proportional resize/centering)
        $lw = (($this->options->logoSpaceWidth - 2) * $this->options->scale);
        $lh = (($this->options->logoSpaceHeight - 2) * $this->options->scale);

        // get the qrcode size
        $ql = ($this->matrix->getSize() * $this->options->scale);

        //dump(options:$this->options, im:$im, w:$w, h:$h, lw:$lw, lh:$lh, ql:$ql);
        // scale the logo and copy it over. done!
        imagecopyresampled($this->image, $im, (($ql - $lw) / 2), (($ql - $lh) / 2), 0, 0, $lw, $lh, $w, $h);

        return $this->dumpImage();
    }

}

