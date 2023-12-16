<?php

namespace App\Controller;

use App\Entity\QrCodeParameter;
use App\Form\QrCodeParameterType;
use App\QrCodeGenerator\QrCodeGenerator;
use App\QrCodeGenerator\QrCodeReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(Request $request)
    {
        $qrCodeParameter = new QrCodeParameter();
        $form = $this->createForm(QrCodeParameterType::class, $qrCodeParameter, [
            'method' => 'GET',
        ]);
        //$form->handleRequest($request);
        $form->submit($request->query->all(), false);

        if ($form->isSubmitted() && $form->isValid()) {

        }

        return $this->render('default/index.html.twig', [
            'form' => $form,
            'qrCodeParameter' => $qrCodeParameter,
        ]);
    }

    #[Route('/qrcode.{format}', name: 'qrcode', defaults: ['format' => 'svg'])]
    #[Cache(maxage: 3600, public: true)]
    public function qrcode(
        #[MapQueryString] ?QrCodeParameter $qrCodeParameter,
        QrCodeGenerator $qrCodeGenerator,
        string $format
    ) {
        $qrCodeParameter ??= new QrCodeParameter();
        $qrCodeParameter->format = $format;

        if ($qrCodeParameter->logoUrl && $format != 'png') {
            return $this->redirectToRoute('qrcode', [
                ...$qrCodeParameter->__toArray(),
                'format' => 'png',
            ]);
        }

        $qrcode = $qrCodeGenerator->generate($qrCodeParameter, $format);

        $contentType = match ($qrCodeParameter->format) {
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            'webp' => 'image/webp',
            default => 'image/svg+xml',
        };
        return new Response(
            file_get_contents($qrcode),
            200,
            [
                'Content-Type' => $contentType,
                'Content-Disposition' => 'inline; filename="qrcode.'.$format.'"',
            ]
        );
    }

    public function check(QrCodeReader $qrCodeReader, ?string $url = null)
    {
        dump($url);
        $data = $qrCodeReader->read($url);
        dump($data);
        return $this->render('default/check.html.twig', [
            'data' => $data,
        ]);
    }

}
