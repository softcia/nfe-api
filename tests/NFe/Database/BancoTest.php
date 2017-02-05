<?php
namespace NFe\Database;

class BancoTest extends \PHPUnit_Framework_TestCase
{
    private $banco;

    protected function setUp()
    {
        $sefaz = \NFe\Core\SEFAZ::getInstance();
        $this->banco = $sefaz->getConfiguracao()->getBanco();
    }

    public function testAliquota()
    {
        $this->banco->fromArray($this->banco);
        $this->banco->fromArray($this->banco->toArray());
        $this->banco->fromArray(null);
        $data = $this->banco->getImpostoAliquota('22021000', 'PR');
        $this->assertArrayHasKey('importado', $data);
        $this->assertArrayHasKey('nacional', $data);
        $this->assertArrayHasKey('estadual', $data);
        $this->assertArrayHasKey('municipal', $data);
        $this->assertArrayHasKey('tipo', $data);
        $this->assertArrayHasKey('info', $data);
        $this->assertArrayHasKey('fonte', $data['info']);
        $this->assertArrayHasKey('versao', $data['info']);
        $this->assertArrayHasKey('chave', $data['info']);
        $this->assertArrayHasKey('vigencia', $data['info']);
        $this->assertArrayHasKey('inicio', $data['info']['vigencia']);
        $this->assertArrayHasKey('fim', $data['info']['vigencia']);
        $this->assertArrayHasKey('origem', $data['info']);

        $data = $this->banco->getImpostoAliquota('22021000', 'ZZ');
        $this->assertFalse($data);
    }

    public function testCodigoMunicipio()
    {
        $codigo = $this->banco->getCodigoMunicipio('Teresina', 'PI');
        $this->assertEquals($codigo, 2211001);

        $codigo = $this->banco->getCodigoMunicipio('São Paulo', 'SP');
        $this->assertEquals($codigo, 3550308);

        $codigo = $this->banco->getCodigoMunicipio('Paranavaí', 'PR');
        $this->assertEquals($codigo, 4118402);

        $this->setExpectedException('\Exception');
        $codigo = $this->banco->getCodigoMunicipio('Paranavaí', 'SP');
        $this->setExpectedException(null);
    }

    public function testCodigoMunicipioEstadoInvalido()
    {
        $this->setExpectedException('\Exception');
        $codigo = $this->banco->getCodigoMunicipio('Inválido', 'ZZ');
        $this->setExpectedException(null);
    }

    public function testCodigoEstado()
    {
        $codigo = $this->banco->getCodigoEstado('PR');
        $this->assertEquals($codigo, 41);

        $codigo = $this->banco->getCodigoEstado('SP');
        $this->assertEquals($codigo, 35);

        $codigo = $this->banco->getCodigoEstado('PI');
        $this->assertEquals($codigo, 22);

        $this->setExpectedException('\Exception');
        $codigo = $this->banco->getCodigoEstado('ZZ');
        $this->setExpectedException(null);
    }

    public function testCodigoOrgao()
    {
        $codigo = $this->banco->getCodigoOrgao('PR');
        $this->assertEquals($codigo, 41);

        $codigo = $this->banco->getCodigoOrgao('SP');
        $this->assertEquals($codigo, 35);

        $codigo = $this->banco->getCodigoOrgao('AN');
        $this->assertEquals($codigo, 91);

        $this->setExpectedException('\Exception');
        $codigo = $this->banco->getCodigoOrgao('ZZ');
        $this->setExpectedException(null);
    }


    public function testInformacaoServico()
    {
        $data = $this->banco->getInformacaoServico(\NFe\Core\Nota::EMISSAO_NORMAL, 'PI');
        $this->assertArrayHasKey('nfe', $data);
        $this->assertArrayHasKey('homologacao', $data['nfe']);
        $this->assertArrayHasKey('inutilizacao', $data['nfe']['homologacao']);
        $this->assertArrayHasKey('protocolo', $data['nfe']['homologacao']);
        $this->assertArrayHasKey('status', $data['nfe']['homologacao']);
        $this->assertArrayHasKey('autorizacao', $data['nfe']['homologacao']);
        $this->assertArrayHasKey('retorno', $data['nfe']['homologacao']);
        $this->assertArrayHasKey('evento', $data['nfe']['homologacao']);

        $this->assertArrayHasKey('producao', $data['nfe']);
        $this->assertArrayHasKey('inutilizacao', $data['nfe']['producao']);
        $this->assertArrayHasKey('protocolo', $data['nfe']['producao']);
        $this->assertArrayHasKey('status', $data['nfe']['producao']);
        $this->assertArrayHasKey('autorizacao', $data['nfe']['producao']);
        $this->assertArrayHasKey('retorno', $data['nfe']['producao']);
        $this->assertArrayHasKey('evento', $data['nfe']['producao']);

        $this->assertArrayHasKey('nfce', $data);
        $this->assertArrayHasKey('homologacao', $data['nfce']);
        $this->assertArrayHasKey('qrcode', $data['nfce']['homologacao']);
        $this->assertArrayHasKey('inutilizacao', $data['nfce']['homologacao']);
        $this->assertArrayHasKey('protocolo', $data['nfce']['homologacao']);
        $this->assertArrayHasKey('status', $data['nfce']['homologacao']);
        $this->assertArrayHasKey('autorizacao', $data['nfce']['homologacao']);
        $this->assertArrayHasKey('retorno', $data['nfce']['homologacao']);
        $this->assertArrayHasKey('evento', $data['nfce']['homologacao']);

        $this->assertArrayHasKey('producao', $data['nfce']);
        $this->assertArrayHasKey('qrcode', $data['nfce']['producao']);
        $this->assertArrayHasKey('inutilizacao', $data['nfce']['producao']);
        $this->assertArrayHasKey('protocolo', $data['nfce']['producao']);
        $this->assertArrayHasKey('status', $data['nfce']['producao']);
        $this->assertArrayHasKey('autorizacao', $data['nfce']['producao']);
        $this->assertArrayHasKey('retorno', $data['nfce']['producao']);
        $this->assertArrayHasKey('evento', $data['nfce']['producao']);

        $data = $this->banco->getInformacaoServico('1', 'PR', 'nfce');
        $data = $this->banco->getInformacaoServico('1', 'PR', '65');
        $this->assertArrayHasKey('homologacao', $data);
        $this->assertArrayHasKey('qrcode', $data['homologacao']);
        $this->assertArrayHasKey('inutilizacao', $data['homologacao']);
        $this->assertArrayHasKey('protocolo', $data['homologacao']);
        $this->assertArrayHasKey('status', $data['homologacao']);
        $this->assertArrayHasKey('autorizacao', $data['homologacao']);
        $this->assertArrayHasKey('retorno', $data['homologacao']);
        $this->assertArrayHasKey('evento', $data['homologacao']);

        $this->assertArrayHasKey('producao', $data);
        $this->assertArrayHasKey('qrcode', $data['producao']);
        $this->assertArrayHasKey('inutilizacao', $data['producao']);
        $this->assertArrayHasKey('protocolo', $data['producao']);
        $this->assertArrayHasKey('status', $data['producao']);
        $this->assertArrayHasKey('autorizacao', $data['producao']);
        $this->assertArrayHasKey('retorno', $data['producao']);
        $this->assertArrayHasKey('evento', $data['producao']);

        $data = $this->banco->getInformacaoServico(\NFe\Core\Nota::EMISSAO_CONTINGENCIA, 'AC', 'nfe');
        $data = $this->banco->getInformacaoServico(\NFe\Core\Nota::EMISSAO_CONTINGENCIA, 'AC', '55');
        $this->assertArrayHasKey('homologacao', $data);
        $this->assertArrayHasKey('protocolo', $data['homologacao']);
        $this->assertArrayHasKey('status', $data['homologacao']);
        $this->assertArrayHasKey('autorizacao', $data['homologacao']);
        $this->assertArrayHasKey('retorno', $data['homologacao']);
        $this->assertArrayHasKey('evento', $data['homologacao']);

        $this->assertArrayHasKey('producao', $data);
        $this->assertArrayHasKey('protocolo', $data['producao']);
        $this->assertArrayHasKey('status', $data['producao']);
        $this->assertArrayHasKey('autorizacao', $data['producao']);
        $this->assertArrayHasKey('retorno', $data['producao']);
        $this->assertArrayHasKey('evento', $data['producao']);

        $data = $this->banco->getInformacaoServico(\NFe\Core\Nota::EMISSAO_NORMAL, 'AC', 'nfe', '1');
        $data = $this->banco->getInformacaoServico(\NFe\Core\Nota::EMISSAO_NORMAL, 'AC', 'nfe', '2');
        $this->assertArrayHasKey('inutilizacao', $data);
        $this->assertArrayHasKey('protocolo', $data);
        $this->assertArrayHasKey('status', $data);
        $this->assertArrayHasKey('autorizacao', $data);
        $this->assertArrayHasKey('retorno', $data);
        $this->assertArrayHasKey('evento', $data);

        // estado inválido
        $this->setExpectedException('\Exception');
        $data = $this->banco->getInformacaoServico('9', 'ZZ');
        $this->setExpectedException(null);
    }

    public function testInformacaoServicoEmissaoInvalida()
    {
        $this->setExpectedException('\Exception');
        $data = $this->banco->getInformacaoServico('10', 'PI');
        $this->setExpectedException(null);
    }

    public function testInformacaoServicoModeloInvalido()
    {
        $this->setExpectedException('\Exception');
        $data = $this->banco->getInformacaoServico(\NFe\Core\Nota::EMISSAO_NORMAL, 'PI', 'nfse');
        $this->setExpectedException(null);
    }

    public function testInformacaoServicoAmbienteInvalido()
    {
        $this->setExpectedException('\Exception');
        $data = $this->banco->getInformacaoServico(\NFe\Core\Nota::EMISSAO_NORMAL, 'PI', 'nfe', 'teste');
        $this->setExpectedException(null);
    }

    public function testNotasTarefas()
    {
        $notas = $this->banco->getNotasAbertas(0, 0);
        $this->assertCount(0, $notas);

        $notas = $this->banco->getNotasPendentes(0, 0);
        $this->assertCount(0, $notas);

        $tarefas = $this->banco->getNotasTarefas(0, 0);
        $this->assertCount(0, $tarefas);
    }
}
