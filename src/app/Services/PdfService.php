<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 08/10/2017
 * Time: 20:17
 */

namespace Greenter\App\Services;


use mikehaertl\wkhtmlto\Pdf;

class PdfService
{
    /**
     * @var string
     */
    private $binPath;
    /**
     * @var array
     */
    private $options;

    /**
     * PdfService constructor.
     * @param string $binPath
     * @param array $options
     */
    public function __construct($binPath, $options)
    {
        $this->binPath = $binPath;
        $this->options = $options;
    }

    /**
     * @param $html
     * @return bool|string
     */
    public function render($html)
    {
        $pdf = new Pdf($this->options);
        /*[
            'no-outline', // Make Chrome not complain
            //'viewport-size' => '1280x1024',
            //'page-width' => '21cm',
            //'page-height' => '29cm',
            'footer-html' => $this->templateDir . '/footer.html',
        ]*/
        $pdf->addPage($html);
        $pdf->binary = $this->binPath;

        return $pdf->toString();
    }
}