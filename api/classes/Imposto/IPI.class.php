<?php
/**
 * MIT License
 * 
 * Copyright (c) 2016 MZ Desenvolvimento de Sistemas LTDA
 * 
 * @author Francimar Alves <mazinsw@gmail.com>
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 */
namespace Imposto;
use Imposto;
use DOMDocument;
use Util;
use ValidationException;

/**
 * Este grupo só precisa ser informado por emissores que sejam
 * contribuintes do IPI ou em uma operação de importação que tenha
 * incidência de IPI
 */
class IPI extends Imposto {

	private $classe;
	private $cnpj;
	private $selo;
	private $quantidade;
	private $enquadramento;
	private $tributo;

	public function __construct($ipi = array()) {
		parent::__construct($ipi);
		$this->setGrupo(self::GRUPO_IPI);
	}

	/**
	 * classe de enquadramento do IPI para Cigarros e Bebidas conforme Atos
	 * Normativos editados pela Receita Federal do Brasil.
	 */
	public function getClasse($normalize = false) {
		if(!$normalize)
			return $this->classe;
		return $this->classe;
	}

	public function setClasse($classe) {
		$this->classe = $classe;
		return $this;
	}

	/**
	 * CNPJ do produtor da mercadoria, quando diferente do emitente nas
	 * exportações direta ou indireta.
	 */
	public function getCNPJ($normalize = false) {
		if(!$normalize)
			return $this->cnpj;
		return $this->cnpj;
	}

	public function setCNPJ($cnpj) {
		$this->cnpj = $cnpj;
		return $this;
	}

	/**
	 * código do Selo de Controle do IPI conforme Atos Normativos editados pela
	 * Receita Federal do Brasil.
	 */
	public function getSelo($normalize = false) {
		if(!$normalize)
			return $this->selo;
		return $this->selo;
	}

	public function setSelo($selo) {
		$this->selo = $selo;
		return $this;
	}

	/**
	 * quantidade de Selo de Controle do IPI utilizados.
	 */
	public function getQuantidade($normalize = false) {
		if(!$normalize)
			return $this->quantidade;
		return Util::toFloat($this->quantidade);
	}

	public function setQuantidade($quantidade) {
		$this->quantidade = $quantidade;
		return $this;
	}

	/**
	 * Código de Enquadramento Legal do IPI, informar 999 enquanto a tabela não
	 * tiver sido criada pela Receita Federal do Brasil
	 */
	public function getEnquadramento($normalize = false) {
		if(!$normalize)
			return $this->enquadramento;
		return $this->enquadramento;
	}

	public function setEnquadramento($enquadramento) {
		$this->enquadramento = $enquadramento;
		return $this;
	}

	public function getTributo() {
		return $this->tributo;
	}

	public function setTributo($tributo) {
		$this->tributo = $tributo;
		return $this;
	}

	/**
	 * Calcula o valor do imposto com base no tributo
	 */
	public function getValor($normalize = false) {
		if(!$normalize)
			return $this->getTributo()->getValor();
		return Util::toCurrency($this->getValor());
	}

	public function toArray() {
		$ipi = parent::toArray();
		$ipi['classe'] = $this->getClasse();
		$ipi['cnpj'] = $this->getCNPJ();
		$ipi['selo'] = $this->getSelo();
		$ipi['quantidade'] = $this->getQuantidade();
		$ipi['enquadramento'] = $this->getEnquadramento();
		$ipi['tributo'] = $this->getTributo();
		return $ipi;
	}

	public function fromArray($ipi = array()) {
		if($ipi instanceof IPI)
			$ipi = $ipi->toArray();
		else if(!is_array($ipi))
			return $this;
		parent::fromArray($ipi);
		$this->setClasse($ipi['classe']);
		$this->setCNPJ($ipi['cnpj']);
		$this->setSelo($ipi['selo']);
		$this->setQuantidade($ipi['quantidade']);
		$this->setEnquadramento($ipi['enquadramento']);
		if(is_null($this->getEnquadramento()))
			$this->setEnquadramento('999');
		$this->setTributo($ipi['tributo']);
		return $this;
	}

	public function getNode($name = null) {
		$dom = new DOMDocument('1.0', 'UTF-8');
		$element = $dom->createElement(is_null($name)?'IPI':$name);
		if(!is_null($this->getClasse())) {
			$element->appendChild($dom->createElement('clEnq', $this->getClasse(true)));
		}
		if(!is_null($this->getCNPJ())) {
			$element->appendChild($dom->createElement('CNPJProd', $this->getCNPJ(true)));
		}
		if(!is_null($this->getSelo())) {
			$element->appendChild($dom->createElement('cSelo', $this->getSelo(true)));
		}
		if(!is_null($this->getQuantidade())) {
			$element->appendChild($dom->createElement('qSelo', $this->getQuantidade(true)));
		}
		$element->appendChild($dom->createElement('cEnq', $this->getEnquadramento(true)));
		if(is_null($this->getTributo()))
			throw new ValidationException(array('tributo' => 'O tributo do imposto IPI não foi informado'));
		$tributo = $this->getTributo()->getNode();
		$tributo = $dom->importNode($tributo, true);
		$element->appendChild($tributo);
		return $element;
	}

}