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
	
	/**
	 * Secuencia de percepciones
	 * @var array
	 */
	private $_percepciones;
	
	public function __construct()
	{
		$this->_manos[]      = new Mano();
		$this->_puntosAgente = 0;
		$this->_puntosHumano = 0;
	}
	
	/**
	 * Actualiza el estado interno de acuerdo a la percepcion recibida
	 * 
	 * @param string $accion
	 * @param Percepcion $percepcion
	 */
	public function actualizar($accion, $percepcion)
	{
	    $this->_percepciones[] = $percepcion;
	    
	    //Como cambia el mundo segun mis acciones
	    $mano = $this->_manoActual();
	    if ($accion) {
	        $percepcionAnterior = $this->_percepcionAnterior();
    	    switch ($accion) {
    	        case 'carta1':
    	            $mano->agregarCartaAgente(
    	                $percepcionAnterior->cartasPropias[0]
    	            );        
    	            break;
    	        case 'carta2':
    	            $mano->agregarCartaAgente(
    	                $percepcionAnterior->cartasPropias[1]
    	            );
    	            break;
    	        case 'carta3':
    	            $mano->agregarCartaAgente(
    	                $percepcionAnterior->cartasPropias[2]
    	            );
    	            break;
    	    }
	    }
	    
	    //Como cambia el mundo segun las acciones del oponente
	    
	}
	
	/**
	 * Devuelve la mano en juego.
	 */
	private function _manoActual()
	{
	    return $this->_manos[count($this->_manos) - 1];
	}
	
	/**
	 * Devuelve la percepcion anterior.
	 */
	private function _percepcionAnterior()
	{
	    return $this->_percepciones[count($this->_manos) - 2];
	}
}