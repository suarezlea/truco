<?php
/**
 * funcion Agente-Reactivo-Simple(percepcion) devuelve una accion
 *     estatico: reglas, conjunto de reglas de condicion-accion
 *
 *     estado <- Interpretar-Entrada(percepcion)
 *     regla  <- Regla-Coincidencia(estado, reglas)
 *     accion <- Regla-Accion(regla)
 *     
 *     devolver accion
 */
class ProgramaAgenteReactivoSimple
{
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