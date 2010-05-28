<?php

class AgenteReactivoSimple extends Agente
{
 	/**
	 * Construye el Programa Agente
	 */
	protected function _crearProgramaAgente()
	{
	    return new ProgramaAgenteReactivoSimple();
	}	
}