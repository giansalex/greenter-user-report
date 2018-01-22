<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 08/10/2017
 * Time: 20:07
 */

namespace Greenter\App\Services;

use Greenter\Model\DocumentInterface;
use Greenter\Parser\DocumentParserInterface;
use Greenter\Xml\Parser\InvoiceParser;
use Greenter\Xml\Parser\NoteParser;
use Psr\Container\ContainerInterface;

class XmlParser implements DocumentParserInterface
{
    /**
     * Prefix namespace.
     */
    const ROOT_PREFIX = 'xs';

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * ReportService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $value
     * @return DocumentInterface
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function parse($value)
    {
        $doc = $this->getDocument($value);
        $docName = $doc->documentElement->nodeName;
        $class = $this->getParserClass($docName);

        if (empty($class)) {
            throw new \Exception('Not found parser for xml with name: '.$docName);
        }
        $parser = $this->container->get($class);

        return $parser->parse($doc);
    }

    private function getParserClass($docName)
    {
        switch ($docName) {
            case 'Invoice':
                return InvoiceParser::class;
            case 'CreditNote':
            case 'DebitNote':
                return NoteParser::class;
        }

        return null;
    }

    /**
     * @param $xml
     * @return \DOMDocument
     */
    public function getDocument($xml)
    {
        $doc = new \DOMDocument();
        $doc->loadXML($xml);

        return $doc;
    }
}