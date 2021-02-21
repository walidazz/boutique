<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;
use Twig\Environment;

class PdfService
{
  private $twig;

  public function __construct(Environment $twig)
  {
    $this->twig = $twig;
  }

  public function generatePdf($template, array $options)
  {
    $pdfOptions = new Options();
    $pdfOptions->set('defaultFont', 'Arial');
    $pdfOptions->setIsRemoteEnabled(true);

    // Instantiate Dompdf with our options
    $dompdf = new Dompdf($pdfOptions);
    // dd($absoluteUrl);
    // $dompdf->set_base_path("public");
    $contxt = stream_context_create([
      'ssl' => [
        'verify_peer' => FALSE,
        'verify_peer_name' => FALSE,
        'allow_self_signed' => TRUE
      ]
    ]);
    $dompdf->setHttpContext($contxt);

    // Retrieve the HTML generated in our twig file
    $html = $this->twig->render($template, $options);

    // Load HTML to Dompdf
    $dompdf->loadHtml($html);

    // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();

    // Output the generated PDF to Browser (force download)
    $dompdf->stream("pdf.pdf", [
      "Attachment" => true
    ]);
  }
}
