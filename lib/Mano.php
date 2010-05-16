<?php

class Mano
{
    /**
     * Cartas jugadas por el agente
     * @var array
     */
	protected $_cartasAgente;

	/**
	 * Cartas jugada por el humano
	 * @var array
	 */
	protected $_cartasHumano;
	
	/**
     * Puntos del agente en la mano
     * @var int
     */
	protected $_puntosAgente;

	/**
	 * Puntos del humano en la mano
	 * @var int
	 */
	protected $_puntosHumano;
	
	/**
	 * Devuelve un valor booleano indicando si la mano terminó
	 */
	public function termino()
	{
		$termino = false;
		//Si jugaron dos cartas cada uno
		if (count($this->_cartasAgente) + count($this->_cartasHumano) == 4) {
			$ventaja = 0;
			foreach (range(0, 1) as $n) {
				if ($this->_cartasAgente[$n]->valor() > 
				    $this->_cartasHumano[$n]->valor()) {
					$ventaja++;
				} elseif ($this->_cartasAgente[$n]->valor() < 
				          $this->_cartasHumano[$n]->valor()) {
					$ventaja--;
				}
			}
			if ($ventaja == 2) {
				$termino = true;
				$this->_puntosAgente = 2;
			} elseif ($ventaja == -2) {
			    $termino = true;
				$this->_puntosHumano = 2;
			} elseif (abs($ventaja) == 1 && 
			    $this->_cartasAgente[1] == $this->_cartasHumano[1]) {
			        
			    $termino = true;
			    if ($this->_cartasAgente[0] > $this->_cartasHumano[0]) {
			        $this->_puntosAgente = 2;
			    } else {
			        $this->_puntosHumano = 2;
			    }
			}
		//Si se jugaron todas las cartas
		} elseif (count($this->_cartasAgente) + 
		          count($this->_cartasHumano) == 6) {
			$termino = true;
			
		    if ($this->_cartasAgente[2]->valor() > 
                $this->_cartasHumano[2]->valor()) {
    	        $this->_puntosAgente = 2;
    	    } else {
    	        $this->_puntosHumano = 2;
    	    }
		} 
		
		return $termino;
	}
	
	/**
	 * Devuelve las cartas jugadas por el agente
	 */
	public function darCartasAgente()
	{
		return (array) $this->_cartasAgente;
	}
	
	/**
	 * Devuelve las cartas jugadas por el humano
	 */
	public function darCartasHumano()
	{
		return (array) $this->_cartasHumano;
	}
	
	/**
	 * Agrega una carta la cojunto de cartas jugadas por el agente
	 * @param Carta $carta
	 */
	public function agregarCartaAgente($carta)
	{ 
		$this->_cartasAgente[] = $carta;
		
		echo 'Carta jugada Agente: ' . $carta . "\n";
	}
	
	/**
	 * Agrega una carta la cojunto de cartas jugadas por el humano
	 * @param Carta $carta
	 */
	public function agregarCartaHumano($carta)
	{
		$this->_cartasHumano[] = $carta;
		
		echo 'Carta jugada Humano: ' . $carta . "\n";
	}
	
	/**
	 * Devuelve la última carta jugada por el humano
	 */
	public function darUltimaCartaHumano()
	{
		return $this->_cartasHumano[count($this->_cartasHumano) - 1];
	}
	
	/**
	 * Devuelve la última carta jugada por el agente
	 */
	public function darUltimaCartaAgente()
	{
		return $this->_cartasAgente[count($this->_cartasAgente) - 1];
	}
	
	/**
	 * Devuelve un valor booleano que indica si en la última ronda aún no se 
	 * jugó ninguna carta
	 */
	public function esPrimeraDeRonda()
	{
		return (count($this->_cartasHumano) == count($this->_cartasAgente));
	}
	
	/**
	 * Devuelve un valor booleano que indica si en la mano aún no se 
	 * jugó ninguna carta
	 */
	public function esNueva()
	{
		return (count($this->_cartasHumano) == 0 && 
		        count($this->_cartasAgente) == 0);
	}
	
	/**
	 * Devuelve los puntos ganados por el agente
	 */
	public function darPuntosAgente()
	{ 
		return $this->_puntosAgente;
	}
	
	/**
	 * Devuelve los puntos ganados por el humano
	 */
	public function darPuntosHumano()
	{ 
		return $this->_puntosHumano;
	}
}