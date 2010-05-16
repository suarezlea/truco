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
	    $this->programa = $this->_crearProgramaAgente();
	}
	
	/**
	 * Construye el Programa Agente
	 */
	protected function _crearProgramaAgente()
	{
	    return new ProgramaAgenteReactivoSimple();
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
	 * Realiza la acción que corresponda en su turno.
	 * @param Mano $mano
	 */
	public function turno($mano)
	{
	    $percepcion = $this->_crearPercepcion($mano);
	    
	    $programa = $this->programa;
	    
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
		if ($es === null) {
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
			
            if ($this->_cartasAgente[2] > $this->_cartasHumano[2]) {
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

$juego = new Juego(); 
$juego->iniciar();