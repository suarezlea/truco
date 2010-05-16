<?php

abstract class Jugador
{
    /**
     * Cartas disponibles para jugar
     * @var array
     */
    protected $_cartas;
	
    /**
     * Bandera que indica si el jugador es mano
     * @var unknown_type
     */
	protected $_esMano;
	
	/**
	 * Recibe una carta del mazo
	 * @param Carta $carta
	 */
	abstract public function recibirCarta($carta);
	
	/**
	 * Decide y ejecuta una accion en su turno
	 * @param Mano $mano
	 */
	abstract public function turno($mano);
	
	/**
	 * Devuelva la carta indicada por el subíndice
	 * @param int $n
	 */
	public function darCarta($n)
	{
		return $this->_cartas[$n];
	}
	
	/**
	 * Permiter devolver y establecer si el jugador es mano
	 * @param mixed $es
	 */
	public function esMano($es = null)
	{
		if ($es === null) {
			return $this->_esMano;
		} else {
			$this->_esMano = $es;
			
			return $es;
		}
	}
	
	/**
	 * Devuelve al mazo las cartas disponibles
	 */
	public function devolverCartas()
	{
		return $this->_cartas = null;
	}
}