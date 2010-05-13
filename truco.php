<?php

class Carta
{
	/**
	 * Número de la carta
	 * @var int
	 */
	private $_numero;
	
	/**
	 * Palo (basto, copa, espada, oro)
	 * @var string
	 */
	private $_palo;
	
	/**
	 * Crea la carta definida por el palo y el número
	 * @param int $numero
	 * @param string $palo
	 */
	public function __construct($numero, $palo)
	{
		$this->_numero = $numero;
		$this->_palo = $palo;
	}
	
	/**
	 * Devuelve la representación en texto de la carta
	 */
	public function __toString() 
	{
		return $this->_numero . " de " . $this->_palo;
	}
	
	/**
	 * Devuelve el valor de la carta.
	 * Este se encuentra definido de menor a mayor según la importancia de la 
	 * carta en el juego
	 */
	public function valor()
	{
		switch ($this->_numero)
		{
			case 4:
				$valor = 1;
				break;
			case 5:
				$valor = 2;
				break;
			case 6:
				$valor = 3;
				break;
			case 7:
				if ($this->_palo == 'copa' || $this->_palo == 'basto') {
					$valor = 4;
				} elseif ($this->_palo == 'oro') {
					$valor = 11;
				} else {
					$valor = 12;
				}
				break;
			case 10:
				$valor = 5;
				break;
			case 11:
				$valor = 6;
				break;
			case 12:
				$valor = 7;
				break;
			case 1:
				if ($this->_palo == 'copa' || $this->_palo == 'oro') {
					$valor = 8;
				} elseif ($this->_palo == 'basto') {
					$valor = 13;
				} else {
					$valor = 14;
				}
				break;
			case 2:
				$valor = 9;
				break;
			case 3:
				$valor = 10;
				break;
		}
		
		return $valor;
	}
}

class Mazo
{

    /**
     * Palos disponibles para el mazo de cartas del Truco 
     * @var array
     */
	private $_palos = array('espada', 'basto', 'oro', 'copa');

	/**
	 * Números disponibles para el mazo de cartas del Truco
	 * @var array
	 */
	private $_numeros = array(1, 2, 3, 4, 5, 6, 7, 10, 11, 12);
	
	/**
	 * Cartas del mazo
	 * @var array
	 */
	private $_cartas;
		
	/**
	 * Crea el mazo con sus respectivas cartas
	 */
	public function __construct()
	{
		$this->_crearCartas();
	}
	
	/**
	 * Crea las cartas del mazo de acuerdo a los palos y números del mazo
	 */
	private function _crearCartas()
	{
		$cartas = array();
		foreach ($this->_palos as $palo) {
			foreach ($this->_numeros as $numero) {
				$cartas[] = new Carta($numero, $palo);
			}
		}
		
		$this->_cartas = $cartas;
	}
	
	public function mezclar()
	{
	    shuffle($this->_cartas);
	}
	
	public function darCarta($n) 
	{
		return $this->_cartas[$n];
	}
	
}

class Percepcion
{
    /**
     * Cartas propias percibidas
     * @var array
     */
    public $cartasPropias;
    /**
     * Carta jugada y percibida
     * @var Carta
     */
	public $cartaOponente;
	
	/**
	 * Canto del oponente percibido
	 * @var unknown_type
	 */
	public $cantoOponente;
}

class Agente extends Jugador
{
    /**
     * Variable que identifica el Programa Agente
     * @var Clousure
     */
    public $programa;

    /**
     * Crea el agente y su programa agente
     */
	public function __construct()
	{
	    //$this->programa = $this->_crearProgramaAgente();
	}
	
	/**
	 * Construye el Programa Agente
	 */
	protected function _crearProgramaAgente()
	{
	    return 
		    function ($percepcion)
		    {
				if ($percepcion->carta) {
					
					// retorna la carta mas baja que mata a la carta del 
					// oponente o en caso de no poder matar retorna la carta 
					// mas baja de todas
					return "carta" . $this->cartaMasBaja($percepcion->carta);
				
				} elseif ($percepcion->canto) {
				
				} else {
					$carta = $this->cartaMasAlta($mano);
				}	
				
		    };
	}
	
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
	 * Juega la carta en su turno.
	 * @param Mano $mano
	 */
	public function turno($mano)
	{
		if ($mano->esPrimeraDeRonda()) {	
			$carta = $this->cartaMasAlta($mano);
		} else {
			$cartaHumano = $mano->darUltimaCartaHumano();
						
			$carta = $this->cartaMasBajaQueMata($mano, $cartaHumano);
			
			if (!$carta) {
				$carta = $this->cartaMasBaja($mano);
			}
		}
		
		$mano->agregarCartaAgente($carta);
		
		echo 'Carta jugada Agente: ' . $carta . "\n";
	}
	
	public function cartaMasAlta($mano)
	{
		foreach (array_reverse($this->_cartas) as $c) {
			if (!in_array($c, $mano->darCartasAgente())) {
				return $c;
			}
		}
	}
	
	public function cartaMasBajaQueMata($mano, $cartaHumano)
	{
		foreach ($this->_cartas as $c) {
			if (!in_array($c, $mano->darCartasAgente()) && 
			    $c->valor() > $cartaHumano->valor()) {
			        
				return $c;
			}
		}
		
		return null;
	}
	
	public function cartaMasBaja($mano)
	{
		foreach ($this->_cartas as $c) {
			if (!in_array($c, $mano->darCartasAgente())) {
				return $c;
			}
		}
	}
}



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
		
		echo 'Carta jugada Humano: ' . $this->darCarta($carta) . "\n";
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
		if ($es ===null) {
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

class Juego
{

    /**
     * Jugador Agente
     * @var Agente
     */
	public $agente;
	
	/**
	 * Jugador Humano
	 * @var Humano
	 */
	public $humano;
	
	/**
	 * Mazo de Cartas
	 * @var Mazo
	 */
	public $mazo;
	
	/**
	 * Manos Jugadas
	 * @var array
	 */
	public $manos;
		
	/**
	 * Crea el Juego con sus respectivos jugadores y mazo de cartas
	 */
	public function __construct()
	{
		$this->agente = new Agente();
		$this->humano = new Humano();
		$this->mazo = new Mazo();
	}
	
	/**
	 * Inicia el Juego
	 * Se juegan tantas manos como se necesiten para terminar el juego
	 */
	public function iniciar()
	{
		while (/*!$this->termino()*/true) {
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
		$this->mazo->mezclar();
		
		$this->repartir();
		
		$this->humano->mostrarCartas();
		
		$this->manos[] = new Mano();
		
		if ($this->humano->esMano()) {
			$this->agente->esMano(true);
			$this->humano->esMano(false);
		} else {
			$this->humano->esMano(true);
			$this->agente->esMano(false);
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
				$this->agente->recibirCarta($this->mazo->darCarta($i));
			else 
				$this->humano->recibirCarta($this->mazo->darCarta($i));
		}
	}
	
	/**
	 * Devuelve la mano actual desde el conjunto de manos jugadas
	 */
	public function manoActual() 
	{
		return $this->manos[count($this->manos) - 1];
	}
	
	/**
	 * Se juega la mano hasta que se termine
	 */
	public function jugarMano()
	{
        $manoActual = $this->manoActual();
		
		while (!$manoActual->termino()) {
			$this->turno();
		}
		
		$this->humano->devolverCartas();
		$this->agente->devolverCartas();
	}
	
	/**
	 * Se juega una carta por cada ronda
	 */
	public function turno()	
	{
		$jugador = $this->quienJuega();
				
		$jugador->turno($this->manoActual());
	}
	
	/**
	 * Define quién es el próxima que juega, según las reglas del Truco
	 * Primero juega el jugador mano, luego el que hay matado la última ronda.
	 * Si empardaron juega le jugador mano. Sino juega el jugador que aún resta
	 * jugar 
	 */
	public function quienJuega()
	{
		$manoActual = $this->manoActual();
		$jugadorMano = $this->agente->esMano() ? $this->agente : $this->humano;
		if($manoActual->esNueva())
			$jugador = $jugadorMano;
		else {
			if (count($manoActual->darCartasAgente()) > 
			    count($manoActual->darCartasHumano())) {
				$jugador = $this->humano;
			} elseif (count($manoActual->darCartasAgente()) < 
			          count($manoActual->darCartasHumano())) {
				$jugador = $this->agente;
			} else {
				$ultimaCartaHumano = $manoActual->darUltimaCartaHumano();
				$ultimaCartaAgente = $manoActual->darUltimaCartaAgente();
				if ($ultimaCartaHumano->valor() > $ultimaCartaAgente->valor()) {
					$jugador = $this->humano;
				} elseif ($ultimaCartaHumano->valor() < 
				          $ultimaCartaAgente->valor()) {
					$jugador = $this->agente;
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
				 ": " . $this->agente->darCarta($i) . "\n"; 
		}
	}
}


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
			if (abs($ventaja) == 2) {
				$termino = true;
			}
		//Si se jugaron todas las cartas
		} elseif (count($this->_cartasAgente) + 
		          count($this->_cartasHumano) == 6) {
			$termino = true;
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
	}
	
	/**
	 * Agrega una carta la cojunto de cartas jugadas por el humano
	 * @param Carta $carta
	 */
	public function agregarCartaHumano($carta)
	{
		$this->_cartasHumano[] = $carta;
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
}

$juego = new Juego(); 
$juego->iniciar();