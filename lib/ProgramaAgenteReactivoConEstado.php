<?php
/**
 * función Agente-Reactivo-Con-Estado(percepción) devuelve una acción
 *     estático: estado, una descripción actual del estado del mundo
 *     			 reglas, conjunto de reglas de condición-acción
 *     			 acción, la acción más reciente, inicialmente ninguna
 *
 *     estado <- Actualizar-Estado(estado, acción, percepción)
 *     regla  <- Regla-Coincidencia(estado, reglas)
 *     acción <- Regla-Acción(regla)
 *     
 *     devolver acción
 */
class ProgramaAgenteReactivoConEstado
{
    /**
     * Una descripción actual del estado del mundo
     * @var Estado
     */
    private $_estado;
    
    /**
     * La acción más reciente, inicialmente ninguna
     * @var string
     */
    private $_accion;
    
    public function __construct()
    {
        $this->_estado = new Estado();
        $this->_accion = null;
    }
    
    public function __invoke($percepcion)
    {
        $this->_estado->actualizar($this->_accion, $percepcion);
        
        $this->_accion = $this->_resolverAccion();
        
        return $this->_accion;
        
        $accion = null;		
        if ($percepcion->cartaOponente) {
    		// retorna la carta mas baja que mata a la carta del 
    		// oponente o en caso de no poder matar retorna la carta 
    		// mas baja de todas
    		$accion = 
    		    $this->_cartaMasBaja(
    		        $percepcion->cartasPropias, 
    		        $percepcion->cartaOponente
    		    );
    	} elseif ($percepcion->cantoOponente) {
    	    //todavia no hacemos nada
    	} else {
    	    //Si tiene que jugar alguna carta, por descarte juega 
    	    //la mas alta
    		$accion = $this->_cartaMasAlta($percepcion->cartasPropias);
    	}   
        
    	return $accion;
    }
    
    private function _cartaMasBaja($cartasPropias, $cartaOponente)
	{
		foreach ($cartasPropias as $k => $c) {
			if ($c->valor() > $cartaOponente->valor()) {
				return 'carta' . ($k + 1);
			}
		}
		
		reset($cartasPropias);
	    $n = key($cartasPropias) + 1;
	    
		return 'carta' . $n;
	}
	
    private function _cartaMasAlta($cartas)
	{
	    end($cartas);
	    $n = key($cartas) + 1;

		return 'carta' . $n;
	}
}