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
        echo 'Ingrese numero de carta a jugar (0, 1, 2): ' . "\n";			
		$carta = trim(fgets(STDIN));
		
		$mano->agregarCartaHumano($this->darCarta($carta));
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
}