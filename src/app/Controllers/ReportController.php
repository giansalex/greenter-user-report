<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 08/10/2017
 * Time: 20:46
 */

namespace Greenter\App\Controllers;

use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Writer;
use Greenter\App\Models\User;
use Greenter\App\Repository\UserRepository;
use Greenter\App\Services\PdfService;
use Greenter\App\Services\ReportService;
use Greenter\Model\Sale\BaseSale;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;

class ReportController
{
    /**
     * @var ReportService
     */
    private $report;
    /**
     * @var PdfService
     */
    private $pdf;
    /**
     * @var string
     */
    private $uploadDir;
    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(UserRepository $repository, ReportService $report, PdfService $pdf, $uploadDir)
    {
        $this->report = $report;
        $this->pdf = $pdf;
        $this->uploadDir = $uploadDir;
        $this->repository = $repository;
    }

    /**
     * @param Request    $request
     * @param Response   $response
     * @param array $args
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index($request, $response, $args)
    {
        $xml = $this->getXmlFromRequest($request);
        if ($xml === false) {
            return $response->withStatus(400);
        }

        /**@var $user User */
        $user = $request->getAttribute('user');
        $setting = $this->repository->getSetting($user->getId());
        $logo_path = $this->uploadDir . DIRECTORY_SEPARATOR . $setting->getLogo();
        $logo = 'data:image/png;base64,' . base64_encode(file_get_contents($logo_path));

        /**@var $inv BaseSale */
        $inv = $this->report->toEntity($xml);

        $qr = 'data:image/png;base64,' . base64_encode($this->getQr($inv));
        $html = $this->report->toHtml($inv, [
            'logo' => $logo,
            'email' => $user->getEmail(),
            'name' => $this->getName($inv),
            'telefono' => '(01) 213456',
            'qrcode' => $qr,
            'montletras' => $this->getMontoLetras($inv),
        ]);

        $pdfRaw = $this->pdf->render($html);

        $response->getBody()->write($pdfRaw);

        $response = $response
            ->withHeader('Content-Type', 'application/pdf')
            ->withHeader('Content-Disposition', "inline;filename='invoice.pdf'")
            ->withHeader('Content-Length', strlen($pdfRaw))
            ->withoutHeader('Pragma')
            ->withoutHeader('Expires')
            ->withoutHeader('Cache-Control');

        return $response;
    }

    private function getXmlFromRequest(Request $request)
    {
        $files = $request->getUploadedFiles();
        /**@var $uploadedFile UploadedFile*/
        if (!isset($files['xml'])) {
            return false;
        }
        $uploadedFile = $files['xml'];
        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            return $uploadedFile->getStream()->getContents();
        }

        return false;
    }

    private function getQr(BaseSale $sale)
    {
        $cl = $sale->getClient();
        $params = [
            $sale->getCompany()->getRuc(),
            $sale->getTipoDoc(),
            $sale->getSerie(),
            $sale->getCorrelativo(),
            number_format($sale->getMtoIGV(), 2, '.', ''),
            number_format($sale->getMtoImpVenta(), 2,'.',''),
            $sale->getFechaEmision()->format('Y-m-d'),
            $cl->getTipoDoc(),
            $cl->getNumDoc(),
        ];
        $content = implode('|', $params) . '|';

        $renderer = new Png();
        $renderer->setHeight(120);
        $renderer->setWidth(120);
        $renderer->setMargin(0);
        $writer = new Writer($renderer);
        $qrCode = $writer->writeString($content, 'UTF-8', ErrorCorrectionLevel::Q);

        return $qrCode;
    }

    private function getMontoLetras(BaseSale $sale) {
        $legs = $sale->getLegends();
        foreach ($legs as $leg) {
            if ($leg->getCode() == '1000') {
                return $leg->getValue();
            }
        }
        return '';
    }

    private function getName(BaseSale $sale)
    {
        $tipo = $sale->getTipoDoc();
        switch ($tipo) {
            case '01':
                return 'FACTURA';
            case '03':
                return 'BOLETA';
            case '07':
                return 'NOTA DE CRÉDITO';
            case '08':
                return 'NOTA DE DÉBITO';
        }

        return '';
    }
}