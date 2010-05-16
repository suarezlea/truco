<?php

require_once 'Jugador.php';
require_once 'Agente.php';
require_once 'Humano.php';
require_once 'Mazo.php';
require_once 'Carta.php';
require_once 'Mano.php';
require_once 'ProgramaAgentereactivoSimple.php';
require_once 'Percepcion.php';

class Juego
{

    /**
     * Jugador Agente
     * @var Agente
     */
	protected $_agente;
	
	/**
	 * Jugador Humano
	 * @var Humano
	 */
	protected $_humano;
	
	/**
	 * Mazo de Cartas
	 * @var Mazo
	 */
	protected $_mazo;
	
	/**
	 * Mano en juego
	 * @var Mano
	 */
	protected $_mano;
	
	/**
	 * Puntos ganados por el agente en el juego
	 * @var int
	 */
	protected $_puntosAgente;
	
	/**
	 * Puntos ganados por el humano en el juego
	 * @var int
	 */
	protected $_puntosHumano;
		
	/**
	 * Crea el Juego con sus respectivos jugadores y mazo de cartas
	 */
	public function __construct()
	{
		$this->_agente       = new Agente();
		$this->_humano       = new Humano();
		$this->_mazo         = new Mazo();
		$this->_puntosAgente = 0;
		$this->_puntosHumano = 0;
	}
	
	/**
	 * Inicia el Juego
	 * Se juegan tantas manos como se necesiten para terminar el juego
	 */
	public function iniciar()
	{
		while (!$this->_termino()) {
			$this->iniciarMano();
			$this->jugarMano();
		}
	}
	
	/**
	* Inica la mano
	* Se mezclan las cartas, se reparten y empieza jugando el jugador que es 
	* mano
	*/
	public function iniciarMano()
	{
		$this->_mazo->mezclar();
		
		$this->repartir();
		
		$this->_humano->mostrarCartas();
		
		$this->_mano = new Mano();
		
		if ($this->_humano->esMano()) {
			$this->_agente->esMano(true);
			$this->_humano->esMano(false);
		} else {
			$this->_humano->esMano(true);
			$this->_agente->esMano(false);
		}
	}

	/**
	 * Reparte las cartas a cada uno de los jugadores
	 */
	public function repartir() 
	{
		for ($i = 0; $i < 6; $i++) {
			$alternar = !$alternar;
		    
			if($alternar)
				$this->_agente->recibirCarta($this->_mazo->darCarta($i));
			else 
				$this->_humano->recibirCarta($this->_mazo->darCarta($i));
		}
	}
	
	/**
	 * Se juega la mano hasta que se termine
	 */
	public function jugarMano()
	{
		while (!$this->_mano->termino()) {
			$this->turno();
		}
		
		$this->_procesarPuntos();
		
		$this->_humano->devolverCartas();
		$this->_agente->devolverCartas();
	}
	
	/**
	 * Se juega una carta por cada ronda
	 */
	public function turno()	
	{
		$jugador = $this->quienJuega();
		$jugador->turno($this->_mano);
	}
	
	/**
	 * Define quién es el próxima que juega, según las reglas del Truco
	 * Primero juega el jugador mano, luego el que hay matado la última ronda.
	 * Si empardaron juega le jugador mano. Sino juega el jugador que aún resta
	 * jugar 
	 */
	public function quienJuega()
	{
		$jugadorMano = 
		    $this->_agente->esMano() ? $this->_agente : $this->_humano;
		if($this->_mano->esNueva())
			$jugador = $jugadorMano;
		else {
			if (count($this->_mano->darCartasAgente()) > 
			    count($this->_mano->darCartasHumano())) {
				$jugador = $this->_humano;
			} elseif (count($this->_mano->darCartasAgente()) < 
			          count($this->_mano->darCartasHumano())) {
				$jugador = $this->_agente;
			} else {
				$ultimaCartaHumano = $this->_mano->darUltimaCartaHumano();
				$ultimaCartaAgente = $this->_mano->darUltimaCartaAgente();
				if ($ultimaCartaHumano->valor() > $ultimaCartaAgente->valor()) {
					$jugador = $this->_humano;
				} elseif ($ultimaCartaHumano->valor() < 
				          $ultimaCartaAgente->valor()) {
					$jugador = $this->_agente;
				} else {
					$jugador = $jugadorMano;
				}
			}
		}
		
		return $jugador;
	}
	
	/**
	 * Muestra por pantalla las cartas del agente
	 */
	public function mostrarCartas()
	{
		for ($i = 0; $i < 3; $i++) {
			echo "Jugador 1 - Carta " . ((string)$i+1) . 
				 ": " . $this->_agente->darCarta($i) . "\n"; 
		}
	}
	
	/**
	 * Devuelve true si el juego termino false en caso contrario
	 */
	protected function _termino()
	{
	    return ($this->_puntosAgente >= 15 || $this->_puntosHumano >= 15);
	}
	
	/**
	 * Aumenta el puntaje de cada jugador de acuerdo a lo ganado
	 * en la mano correspondiente
	 */
	protected function _procesarPuntos()
	{
	    $this->_puntosAgente += $this->_mano->darPuntosAgente();
	    $this->_puntosHumano += $this->_mano->darPuntosHumano();
	    
	    echo 'Puntos Humano: ' . $this->_puntosHumano . "\n";
	    echo 'Puntos Agente: ' . $this->_puntosAgente . "\n";
	}
	
}