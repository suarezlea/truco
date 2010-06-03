<?php

class AgenteReactivoConEstado extends Agente
{
 	/**
	 * Construye el Programa Agente
	 */
	protected function _crearProgramaAgente()
	{
	    return new ProgramaAgenteReactivoConEstado();
	}	
}