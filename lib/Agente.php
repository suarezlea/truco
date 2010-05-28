<?php

abstract class Agente extends Jugador
{
    /**
     * Variable que identifica el Programa Agente
     * @var Clousure
     */
    protected $_programa;

    /**
     * Crea el agente y su programa agente
     */
	public function __construct()
	{
	    $this->_programa = $this->_crearProgramaAgente();
	}
	
	/**
	 * Construye el Programa Agente
	 */
	abstract protected function _crearProgramaAgente();
		
	/**
	 * Recibe la carta $carta y se agrega al conjunto de cartas disponibles
	 * ordenadas de mayor a menor 
	 * @param Carta $carta
	 */
	public function recibirCarta($carta)
	{
		$this->_cartas[] = $carta;
		
		$ordenar = 
		    function($cartaA, $cartaB) 
		    {
		        if ($cartaA->valor() == $cartaB->valor()) { 
		            return 0;
		        }

                return ($cartaA->valor() < $cartaB->valor()) ? -1 : 1;
		    };
		 
		usort(
		    $this->_cartas,
		    $ordenar 
		);
	}
	
	/**
	 * Realiza la acción que corresponda en su turno.
	 * @param Mano $mano
	 */
	public function turno($mano)
	{
	    $percepcion = $this->_crearPercepcion($mano);
	    
	    $programa = $this->_programa;
	    
	    $accion = $programa($percepcion);
	    
	    $this->_realizarAccion($accion, $mano);
	}

	private function _crearPercepcion($mano)
	{
	    $cartasPropias = array_diff($this->_cartas, $mano->darCartasAgente());
	    
	    if ($mano->esPrimeraDeRonda()) {
	        $cartaOponente = null;
	    } else {
	        $cartaOponente = $mano->darUltimaCartaHumano();
	    }
	    
	    $cantoOponente = null;
	    
	    $percepcion = new Percepcion();
	    $percepcion->cartasPropias = $cartasPropias;
	    $percepcion->cartaOponente = $cartaOponente;
	    $percepcion->cantoOponente = $cantoOponente;
	       
	    return $percepcion;
	}
	
	private function _realizarAccion($accion, $mano)
	{
	    switch ($accion) {
	        case 'carta1':
	            $mano->agregarCartaAgente($this->darCarta(0));        
	            break;
	        case 'carta2':
	            $mano->agregarCartaAgente($this->darCarta(1));
	            break;
	        case 'carta3':
	            $mano->agregarCartaAgente($this->darCarta(2));
	            break;
	    }
	}
}