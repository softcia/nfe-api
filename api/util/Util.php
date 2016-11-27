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

class Util {
	
	public static function toCurrency($value)
	{
		return number_format($value, 2, '.', '');
	}

	public static function toFloat($value)
	{
		return number_format($value, 4, '.', '');
	}

	public static function toDateTime($time)
	{
		return date('Y-m-d\TH:i:sP', $time);
	}

	public static function isEqual($value, $compare, $delta = 0.005) {
		return $compare < ($value + $delta) && ($value - $delta) < $compare;
	}

	public static function isGreater($value, $compare, $delta = 0.005) {
		return $value > ($compare + $delta);
	}

	public static function isLess($value, $compare, $delta = 0.005) {
		return ($value + $delta) < $compare;
	}
	
	public static function toMoney($value)
	{
		return 'R$ '.number_format($value, 2, ',', '.');
	}

	/**
	 * Retorna o módulo dos dígitos por 11
	 *
	 * @param string $digitos
	 *
	 * @return int
	 */
	static public function getModulo11($digitos)
	{
		$sum = 0;
		$mul = 1;
		$len = strlen($digitos);
		for ($i = $len - 1; $i >= 0; $i--) { 
			$mul++;
			$dig = $digitos[$i];
			$sum += $dig * $mul;
			if($mul == 9)
				$mul = 1; // reset
		}
		return $sum % 11;
	}

	/**
	 * Retorna o módulo dos dígitos por 10
	 *
	 * @param string $digitos
	 *
	 * @return int
	 */
	static public function getModulo10($digitos)
	{
		$sum = 0;
		$mul = 1;
		$len = strlen($digitos);
		for ($i = $len - 1; $i >= 0; $i--) { 
			$mul++;
			$dig = $digitos[$i];
			$term = $dig * $mul;
			$sum += ($dig == 9)?$dig:($term % 9);
			if($mul == 2)
				$mul = 0; // reset
		}
		return $sum % 10;
	}

	/**
	 * Retorna o Dígito de Auto-Conferência dos dígitos
	 *
	 * @param string $digitos
	 * @param int $div Número divisor que determinará o resto da divisão
	 * @param int $presente Informa o número padrão para substituição do excesso
	 *
	 * @return int
	 */
	static public function getDAC($digitos, $div, $presente = 0)
	{
		$ext = $div % 10;
		if($div == 10)
			$ret = self::getModulo10($digitos);
		else
			$ret = self::getModulo11($digitos);
		return ($ret <= $ext)? $presente: ($div - $ret);
	}

}