<?php

set_include_path(
    implode(
        PATH_SEPARATOR, array(
            realpath(__DIR__ . '/lib'),
            get_include_path(),
        )
    )
);

require_once 'Juego.php';

$juego = new Juego(); 
$juego->iniciar();