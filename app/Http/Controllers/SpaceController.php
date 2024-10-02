<?php

namespace App\Http\Controllers;

use App\Models\space;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SpaceController extends Controller {

    public function index (): JsonResponse{
        $spaces = space::all();
        return response()->json($spaces, 200);
    }

    public function show($id): JsonResponse{
        $space = Space::where('space_id', $id)->first();
        return response()->json($space, 200);
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

        $space = Space::where('space_id', $request->input('id'))->first();

        if($space == null) return response()->json('El espacio no existe', 200);

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'string|max:255',
                'description' => 'string|max:500',
                'capacity' => 'integer',
                'avail_from' => 'date', 
                'avail_to' => 'date|after:space_avail_from', 
                'price_hour' => 'numeric|min:0', 
            ]);

            if($validator->fails()) throw new ValidationException($validator);

            $spaceValidated = $validator->validated();
            $spaceUpdated = [];

            if($request->has('name')){
                $spaceUpdated['space_name'] = $spaceValidated['name'];
            }

            if($request->has('description')){
                $spaceUpdated['space_desc'] = $spaceValidated['description'];
            }

            if($request->has('capacity')){
                $spaceUpdated['space_capac'] = $spaceValidated['capacity'];
            }

            if($request->has('avail_from')){
                $spaceUpdated['space_avail_from'] = $spaceValidated['avail_from'];
            }

            if($request->has('avail_to')){
                $spaceUpdated['space_avail_to'] = $spaceValidated['avail_to'];
            }

            if($request->has('price_hour')){
                $spaceUpdated['space_price_hour'] = $spaceValidated['price_hour'];
            }

            $space->update($spaceUpdated);

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