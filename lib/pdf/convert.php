<?php

require __DIR__.'/vendor/autoload.php';

/**
 * Class Convert
 */
class Convert
{
    /**
     * @param $xml
     * @return \Greenter\Model\DocumentInterface
     */
    public static function toEntity($xml)
    {
        $parser = self::getParser($xml);
        return $parser->parse($xml);
    }

    /**
     * @param \Greenter\Model\DocumentInterface $doc
     * @param array $parameters
     * @return bool|string
     */
    public static function toHtml($doc, $parameters)
    {
        $report = new \Greenter\Report\HtmlReport(__DIR__.'/templates', ['cache' => sys_get_temp_dir()]);
        $report->setTemplate('invoice.html.twig');
        $html = $report->build($doc, $parameters);
        $inv = new \Greenter\Model\Sale\Invoice();
        $inv->getFechaEmision();

        return $html;
    }

    public static function toPdf($html)
    {
        $pdf = new \mikehaertl\wkhtmlto\Pdf([
            'no-outline', // Make Chrome not complain
            //'viewport-size' => '1280x1024',
            //'page-width' => '21cm',
            //'page-height' => '29cm',
            'footer-html' => __DIR__.'/templates/footer.html',
        ]);
        $pdf->addPage($html);
        $pdf->binary = __DIR__.'/bin/wkhtmltopdf.exe';

        return $pdf->toString();
    }

    private static function getParser($xml)
    {
        $parser = new \Greenter\Xml\Parser\InvoiceParser();

        return $parser;
    }
}