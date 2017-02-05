<?php
namespace NFe\Entity\Imposto\ICMS;

class MistaTest extends \PHPUnit_Framework_TestCase
{
    private $resource_path;

    protected function setUp()
    {
        $this->resource_path = dirname(dirname(dirname(dirname(__DIR__)))).'/resources';
    }

    public function testMistaXML()
    {
        $icms_mista = new \NFe\Entity\Imposto\ICMS\Mista();
        $icms_mista->getNormal()->setModalidade(\NFe\Entity\Imposto\ICMS\Normal::MODALIDADE_OPERACAO);
        $icms_mista->getNormal()->setBase(90.00);
        $icms_mista->getNormal()->setReducao(10.00);
        $icms_mista->getNormal()->setAliquota(18.00);

        $icms_mista->setModalidade(\NFe\Entity\Imposto\ICMS\Parcial::MODALIDADE_AGREGADO);
        $icms_mista->setBase(162.00);
        $icms_mista->setMargem(100.00);
        $icms_mista->setReducao(10.00);
        $icms_mista->setAliquota(18.00);
        $icms_mista->fromArray($icms_mista);
        $icms_mista->fromArray($icms_mista->toArray());
        $icms_mista->fromArray(null);

        $xml = $icms_mista->getNode();
        $dom = $xml->ownerDocument;

        $dom_cmp = new \DOMDocument();
        $dom_cmp->preserveWhiteSpace = false;
        $dom_cmp->load($this->resource_path . '/xml/imposto/icms/testMistaXML.xml');
        $xml_cmp = $dom_cmp->saveXML($dom_cmp->documentElement);
        $this->assertXmlStringEqualsXmlString($xml_cmp, $dom->saveXML($xml));

        // $dom->formatOutput = true;
        // file_put_contents(
        //     $this->resource_path . '/xml/imposto/icms/testMistaXML.xml',
        //     $dom->saveXML($xml)
        // );
    }

    public function testMistaLoadXML()
    {
        $dom_cmp = new \DOMDocument();
        $dom_cmp->preserveWhiteSpace = false;
        $dom_cmp->load($this->resource_path . '/xml/imposto/icms/testMistaXML.xml');

        $icms_mista = new \NFe\Entity\Imposto\ICMS\Mista();
        $icms_mista->loadNode($dom_cmp->documentElement);

        $xml = $icms_mista->getNode();
        $dom = $xml->ownerDocument;

        $xml_cmp = $dom_cmp->saveXML($dom_cmp->documentElement);
        $this->assertXmlStringEqualsXmlString($xml_cmp, $dom->saveXML($xml));
    }
}
