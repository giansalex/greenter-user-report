<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 29/01/2018
 * Time: 06:34 PM
 */

namespace Greenter\App\Services;

use Greenter\Model\Despatch\Despatch;
use Greenter\Model\DocumentInterface;
use Greenter\Model\Perception\Perception;
use Greenter\Model\Retention\Retention;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\Note;
use Greenter\Model\Summary\Summary;
use Greenter\Model\Voided\Reversion;
use Greenter\Model\Voided\Voided;
use Greenter\Report\HtmlReport;
use Greenter\Report\ReportInterface;

class SueReport implements ReportInterface
{
    /**
     * @var HtmlReport
     */
    private $report;

    /**
     * SueReport constructor.
     * @param HtmlReport $report
     */
    public function __construct(HtmlReport $report)
    {
        $this->report = $report;
    }

    /**
     * @param DocumentInterface $document
     * @param array $parameters
     *
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render(DocumentInterface $document, $parameters = [])
    {
        $template = $this->getTemplate($document);
        if (empty($template)) {
            throw new \RuntimeException('Template not found');
        }

        $this->report->setTemplate($template);

        return $this->report->render($document, $parameters);
    }

    private function getTemplate(DocumentInterface $document)
    {
        $className = get_class($document);

        switch ($className) {
            case Invoice::class:
            case Note::class:
                return 'invoice.html.twig';
            case Summary::class:
                return 'summary.html.twig';
            case Despatch::class:
                return 'despatch.html.twig';
            case Voided::class:
            case Reversion::class:
                return 'voided.html.twig';
            case Retention::class:
                return 'retention.html.twig';
            case Perception::class:
                return 'perception.html.twig';
        }

        return null;
    }
}