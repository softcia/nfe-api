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

/**
 * Classe para validação da nota fiscal eletrônica
 */
class NFe extends NFBase {
	private $id;

	public function __construct($nfe = array()) {
		if(is_array($nfe)) {
			$this->setID($nfe['id']);
		}
	}

	public function getID() {
		return $this->id;
	}

	public function setID($id) {
		$this->id = $id;
	}

	public function toArray() {
		$nfe = array();
		$nfe['id'] = $this->getID();
		return $nfe;
	}

	private static function validarCampos(&$nfe) {
		$erros = array();
		if(!is_numeric($nfe['id']))
			$erros['id'] = 'O ID não foi informado';
		if(!empty($erros))
			throw new ValidationException($erros);
	}

	private static function initSearch() {
		return   DB::$pdo->from('NFe');
	}

	public static function getTodos($inicio = null, $quantidade = null) {
		$query = self::initSearch();
		if(!is_null($inicio) && !is_null($quantidade)) {
			$query = $query->limit($quantidade)->offset($inicio);
		}
		$_nfes = $query->fetchAll();
		$nfes = array();
		foreach($_nfes as $nfe)
			$nfes[] = new NFe($nfe);
		return $nfes;
	}

	public static function getCount() {
		$query = self::initSearch();
		return $query->count();
	}

}
