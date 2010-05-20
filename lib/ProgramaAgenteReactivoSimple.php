<?php

/**
 * funcion Agente-Reactivo-Simple(percepcion) devuelve una accion
 *     estatico: reglas, conjunto de reglas de condicion-accion
 *
 *     estado <- Interpretar-Entrada(percepcion)
 *     regla  <- Regla-Coincidencia(estado, reglas)
 *     accion <- Regla-Accion(regla)
 *     
 *     revolver accion
 */
class ProgramaAgenteReactivoSimple
{
    const ENVIDO = 27;
    
    public function __invoke($percepcion)
    {
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
    	} elseif ($percepcion->cantoOponente == 'envido') {
    	    if ($this->_envido($percepcion->cartasPropias)) {
    	        $accion = 'quiero';
    	    } else {
    	        $accion = 'noquiero';
    	    }
    	} else {
    	    //Si puede cantar envido
    	    if (count($percepcion->cartasPropias) == 3 &&
    	        $this->_envido($percepcion->cartasPropias)) {
    	        $accion = 'envido';
    	    } else {
        	    //Si tiene que jugar alguna carta, por descarte juega 
        	    //la mas alta
        		$accion = $this->_cartaMasAlta($percepcion->cartasPropias);
    	    }
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
	
	private function _envido($cartas)
	{
        $envido = array();
        $count = array();
        foreach ($cartas as $c) {
            $envido[$c->darPalo()] += 
                ($c->darNumero() > 7) ? 0 : $c->darNumero();
            $count[$c->darPalo()]++;
        }
        
        foreach ($envido as $k => $e) {
            if ($count[$k] > 1) {
                $envido[$k] += 20;    
            }
        }
        
        return max($envido) >= self::ENVIDO;
	}
}