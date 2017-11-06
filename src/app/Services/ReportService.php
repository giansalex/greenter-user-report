<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 08/10/2017
 * Time: 20:07
 */

namespace Greenter\App\Services;

use Greenter\Report\HtmlReport;
use Greenter\Xml\Parser\InvoiceParser;

class ReportService
{
    /**
     * @var ReportService
     */
    private $report;

    /**
     * ReportService constructor.
     * @param HtmlReport $report
     */
    public function __construct(HtmlReport $report)
    {
        $this->report = $report;
    }

    /**
     * @param $xml
     * @throws \Exception
     * @return \Greenter\Model\DocumentInterface
     */
    public function toEntity($xml)
    {
        $doc = new \DOMDocument();
        $doc->loadXML($xml);
        $parser = self::getParser();
        if (empty($parser)) {
            throw new \Exception('not found parser for xml');
        }

        return $parser->parse($doc);
    }

    /**
     * @param \Greenter\Model\DocumentInterface $doc
     * @param array $parameters
     * @return bool|string
     */
    public function toHtml($doc, $parameters)
    {
        $this->report->setTemplate('invoice.html.twig');
        $html = $this->report->render($doc, $parameters);

        return $html;
    }

    private function getParser()
    {
       return new InvoiceParser();
    }
}