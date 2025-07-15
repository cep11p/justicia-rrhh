<?php

namespace App\Services;

use App\Models\Concepto;
use App\Models\Empleado;
use App\Models\ValorConcepto;
use App\Models\LiquidacionConcepto;
use App\Models\LiquidacionEmpleado;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ConceptoService
{

    public function calcularBasico()
    {
        // TODO: Implementar lógica de cálculo específica

        //vamos a crear un liquidacion_concepto con concepto basico
        //por lo tanto buscamos el valor de ese concepto en valor_concepto
        //instanciamos el cargo del empleado del periodo dado
        //buscamos el valor del cargo instanciado

        //crear funcion en empleado que me

        //obtener valor del cargo de ese periodo, si por ejemplo el empleado empieza a mitad de mes,
        //los dias son el porcentual trabajo en ese mes

        //obtener valor del cargo que tienen las designaciones de ese periodo para el empleado, tener en cuenta
        //que si son mas de una designacion, el valor el porcentual es por los dias trabajado con ese cargo


        //en el caso que tenga solo un cargo en ese periodo, devolver solo el valor de ese cargo

        //si tiene mas de un cargo en ese periodo, devolver el valor por cargo
}
