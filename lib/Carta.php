<?php

class Carta
{
	/**
	 * Número de la carta
	 * @var int
	 */
	private $_numero;
	
	/**
	 * Palo (basto, copa, espada, oro)
	 * @var string
	 */
	private $_palo;
	
	/**
	 * Crea la carta definida por el palo y el número
	 * @param int $numero
	 * @param string $palo
	 */
	public function __construct($numero, $palo)
	{
		$this->_numero = $numero;
		$this->_palo = $palo;
	}
	
	/**
	 * Devuelve la representación en texto de la carta
	 */
	public function __toString() 
	{
		return $this->_numero . " de " . $this->_palo;
	}
	
	/**
	 * Devuelve el valor de la carta.
	 * Este se encuentra definido de menor a mayor según la importancia de la 
	 * carta en el juego
	 */
	public function valor()
	{
		switch ($this->_numero)
		{
			case 4:
				$valor = 1;
				break;
			case 5:
				$valor = 2;
				break;
			case 6:
				$valor = 3;
				break;
			case 7:
				if ($this->_palo == 'copa' || $this->_palo == 'basto') {
					$valor = 4;
				} elseif ($this->_palo == 'oro') {
					$valor = 11;
				} else {
					$valor = 12;
				}
				break;
			case 10:
				$valor = 5;
				break;
			case 11:
				$valor = 6;
				break;
			case 12:
				$valor = 7;
				break;
			case 1:
				if ($this->_palo == 'copa' || $this->_palo == 'oro') {
					$valor = 8;
				} elseif ($this->_palo == 'basto') {
					$valor = 13;
				} else {
					$valor = 14;
				}
				break;
			case 2:
				$valor = 9;
				break;
			case 3:
				$valor = 10;
				break;
		}
		
		return $valor;
	}
	
	/**
	 * Devuelve el palo de la carta
	 */
	public function darPalo()
	{
	    return $this->_palo;
	}
	
	/**
	 * Devuelve el número de la carta
	 */
    public function darNumero()
	{
	    return $this->_numero;
	}
}