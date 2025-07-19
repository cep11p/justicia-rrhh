<?php

namespace App\Http\Controllers;

use App\Http\Requests\LiquidacionStoreRequest;
use App\Http\Resources\LiquidacionResource;
use App\Models\Liquidacion;
use App\Services\LiquidacionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LiquidacionController extends Controller
{

    protected $service;

    public function __construct(LiquidacionService $liquidacionService)
    {
        $this->service = $liquidacionService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $list = $this->service->list($request);
            return LiquidacionResource::collection($list);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LiquidacionStoreRequest $request)
    {
        $record = $this->service->store($request->validated());

        return response()->json([
            'message' => 'Liquidación creada satisfactoriamente',
            'data' => $record->getAttributes()
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Liquidacion $liquidacion)
    {
        return new LiquidacionResource($liquidacion);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Liquidacion $liquidacion)
    {
        $liquidacion->delete();
        return response()->json([
            'message' => 'Liquidación eliminada satisfactoriamente'
        ], 200);
    }

    /**
     * Se detalla la liquidación en un PDF
     */
    public function viewToPdf(Liquidacion $liquidacion)
    {
        $data = LiquidacionResource::make($liquidacion)->toArray(request());

        $pdf = Pdf::loadView('pdf.recibo', compact('data'));

        return $pdf->stream('recibo_' . $data['empleado']['legajo'] . '_' . $data['periodo'] . '.pdf');
    }
}
