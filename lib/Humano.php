<?php

class Humano extends Jugador
{
    /**
	 * Recibe la carta $carta y se agrega al conjunto de cartas disponibles 
	 * para jugar
	 * @param Carta $carta
	 */
	public function recibirCarta($carta)
	{
		$this->_cartas[] = $carta;
	}
	
	/**
	 * Solicita por pantalla la carta a jugar, una vez jugada se agrega
	 * al grupo de cartas jugadas en la mano
	 * @param Mano $mano
	 */
    public function turno($mano) 
    {
        echo 
			'Ingrese la opción: ' . "\n". 
			'1 - Carta 1' . "\n" .
			'2 - Carta 2' . "\n" .
			'3 - Carta 3' . "\n" .
			'4 - Envido' . "\n" .
			'5 - Quiero' . "\n" .
			'6 - No Quiero' . "\n";

		$opcion = trim(fgets(STDIN));
		
		$this->_realizarOpcion($opcion, $mano);
    }
   
    /**
     * Muestra por pantalla las cartas del jugador
     */
    public function mostrarCartas()
    {
		echo 'Cartas Humano: ' . "\n";
		
		foreach ($this->_cartas as $carta) {
			echo $carta. "\n";
		}
    }
    
    private function _realizarOpcion($opcion, $mano)
	{
	    switch ($opcion) {
	        case '1':
	            $mano->agregarCartaHumano($this->darCarta(0));        
	            break;
	        case '2':
	            $mano->agregarCartaHumano($this->darCarta(1));
	            break;
	        case '3':
	            $mano->agregarCartaHumano($this->darCarta(2));
	            break;
	        case '4':
	            $mano->agregarCantoHumano('envido');
	            break;
	        case '5':
	            $mano->agregarCantoHumano('quiero');
	            break;
	        case '6':
	            $mano->agregarCantoHumano('noquiero');
	            break;
	    }
	}
}