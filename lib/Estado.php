<?php

class Estado
{
	/**
	 * Manos jugadas.
	 * @var array
	 */
	private $_manos;
	
	/**
	 * Puntaje actual del agente
	 * @var int
	 */
	private $_puntosAgente;
	
	/**
	 * Puntaje actual del humano
	 * @var int
	 */
	private $_puntosHumano;
	
	public function __construct()
	{
		$this->_manos[]      = new Mano();
		$this->_puntosAgente = 0;
		$this->_puntosHumano = 0;
	}
	
	/**
	 * Devuelve la mano en juego.
	 */
	private function _manoActual()
	{
	    return $this->_manos[count($this->_manos) - 1];
	}
}