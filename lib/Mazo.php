<?php

class Mazo
{

    /**
     * Palos disponibles para el mazo de cartas del Truco 
     * @var array
     */
	private $_palos = array('espada', 'basto', 'oro', 'copa');

	/**
	 * Números disponibles para el mazo de cartas del Truco
	 * @var array
	 */
	private $_numeros = array(1, 2, 3, 4, 5, 6, 7, 10, 11, 12);
	
	/**
	 * Cartas del mazo
	 * @var array
	 */
	private $_cartas;
		
	/**
	 * Crea el mazo con sus respectivas cartas
	 */
	public function __construct()
	{
		$this->_crearCartas();
	}
	
	/**
	 * Crea las cartas del mazo de acuerdo a los palos y números del mazo
	 */
	private function _crearCartas()
	{
		$cartas = array();
		foreach ($this->_palos as $palo) {
			foreach ($this->_numeros as $numero) {
				$cartas[] = new Carta($numero, $palo);
			}
		}
		
		$this->_cartas = $cartas;
	}
	
	public function mezclar()
	{
	    shuffle($this->_cartas);
	}
	
	public function darCarta($n) 
	{
		return $this->_cartas[$n];
	}
	
}