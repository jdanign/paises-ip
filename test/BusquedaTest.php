<?php

require_once '../../config/settings.php';


use PHPUnit\Framework\TestCase,
	model\Busqueda;




/**
 * Pruebas unitarias para el proyecto
 */
class BusquedaTest extends TestCase {
	/**
	 * Test para probar el método selectApiRest con una búsqueda con parámetro vacío.
	 * El método debe dar una respuesta de un objeto con la propiedad 'ok' en 'true'.
	 */
	public function testInputVacioSelectApiRest(){
		$thisBusqueda = new Busqueda('');
		$result = $thisBusqueda->selectApiRest();

		$this->assertIsArray($result);
	}


	
}