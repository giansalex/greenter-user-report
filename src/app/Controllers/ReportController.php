<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 08/10/2017
 * Time: 20:46.
 */

namespace Greenter\App\Controllers;

use Greenter\App\Models\User;
use Greenter\App\Repository\UserRepository;
use Greenter\Model\Sale\BaseSale;
use Greenter\Parser\DocumentParserInterface;
use Greenter\Report\ReportInterface;
use Greenter\Report\XmlUtils;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;

class ReportController
{
    /**
     * @var ReportInterface
     */
    private $report;
    /**
     * @var string
     */
    private $uploadDir;
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var DocumentParserInterface
     */
    private $parser;
    /**
     * @var XmlUtils
     */
    private $utils;

    public function __construct(
        UserRepository $repository,
        DocumentParserInterface $parser,
        ReportInterface $report,
        XmlUtils $utils,
        $uploadDir
    ) {
        $this->report = $report;
        $this->uploadDir = $uploadDir;
        $this->repository = $repository;
        $this->parser = $parser;
        $this->utils = $utils;
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index($request, $response, $args)
    {
        $xml = $this->getXmlFromRequest($request);
        if ($xml === false) {
            return $response->withStatus(400);
        }

        /** @var $user User */
        $user = $request->getAttribute('user');
        $setting = $this->repository->getSetting($user->getId());
        $logo_path = $this->uploadDir.DIRECTORY_SEPARATOR.$setting->getLogo();
        $logo = file_exists($logo_path) ? file_get_contents($logo_path) : '';

        /** @var $inv BaseSale */
        $inv = $this->parser->parse($xml);

        $params = [
            'system' => [
                'logo' => $logo,
            ],
            'user' => [
                'resolucion' => '-',
//                'header' => 'Email: <b>'.$user->getEmail().'</b>',
                'footer' => '<p style="font-size: 8pt">Código Hash '.$this->utils->getHashSign($xml).'</p>',
            ],
        ];
        $pdfRaw = $this->report->render($inv, $params);

        $response->getBody()->write($pdfRaw);
        $response = $response
            ->withHeader('Content-Type', 'application/pdf')
            ->withHeader('Content-Disposition', 'attachment;filename="'.$inv->getName().'.pdf"')
            ->withHeader('Content-Length', strlen($pdfRaw));

        return $response;
    }

    private function getXmlFromRequest(Request $request)
    {
        $files = $request->getUploadedFiles();
        /** @var $uploadedFile UploadedFile */
        if (!isset($files['xml'])) {
            return false;
        }
        $uploadedFile = $files['xml'];
        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            return $uploadedFile->getStream()->getContents();
        }

        return false;
    }
}
