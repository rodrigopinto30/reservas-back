<?php

namespace App\Http\Controllers;

use App\Models\Space;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SpaceController extends Controller {

    public function index (): JsonResponse{
        $spaces = Space::all();
        return response()->json($spaces, 200);
    }

    public function show($id): JsonResponse{
        $space = Space::where('id', $id)->first();
        return response()->json($space, 200);
    }
    
    public function lastSpace (): JsonResponse{
        $spaces = Space::latest()->take(5)->get();
        return response()->json($spaces, 200);
    }

    public function store(Request $request): JsonResponse{
        
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:500',
                'capacity' => 'required|integer',
                'avail_from' => 'required|date', 
                'avail_to' => 'required|date|after:space_avail_from', 
                'price_hour' => 'required|numeric|min:0', 
            ]);

            if($validator->fails()) throw new ValidationException($validator);
            
            $space = $validator->validated();
            
            Space::create([
                'space_name' => $space['name'],
                'space_capacity' => $space['capacity'],
                'space_avail_from' => $space['avail_from'],
                'space_avail_to' => $space['avail_to'],
                'space_price_hour' => $space['price_hour']
            ]);

            return response()->json('Espacio almacenado éxitosamente.', 200);

        } catch (ValidationException $e) {
            return response()->json([
                'error' =>'Fallo la validación de los datos.',
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function update(Request $request): JsonResponse {

        try {
            $space = Space::findOrFail($request->input('id'));

            $validator = Validator::make($request->all(), [
                'name' => 'nullable|string|max:255',
                'capacity' => 'nullable|integer',
                'avail_from' => 'nullable|date', 
                'avail_to' => 'nullable|date|after:space_avail_from', 
                'price_hour' => 'nullable|numeric|min:0', 
            ]);

            if($validator->fails()) throw new ValidationException($validator);

            $space->space_name = $request->input('space_name', $space->space_name);
            $space->space_capacity = $request->input('space_capacity', $space->space_capac);
            $space->space_avail_from = $request->input('space_avail_from', $space->space_avail_from);
            $space->space_avail_to = $request->input('space_avail_to', $space->space_avail_to);
            
            $space->save();

            return response()->json([
                'message' => 'El espacio fue modificado éxitosamente'
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Fallo la validacón de los datos.',
                'message' => $e->getMessage()
            ], 422);
        }
        return response()->json('El espacio fue actualizado.', 200);
    }

    public function destroy($id) {
        $space = Space::findOrFail($id); 
        $space->delete();
        return response()->json([
            'message' => 'El espacio fue eliminado exitosamente'
        ], 200);
    }
}