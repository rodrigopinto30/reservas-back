<?php

namespace App\Http\Controllers;

use App\Models\reservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ReservationController extends Controller {
    
    public function index(): JsonResponse{
        $reservations = Reservation::all();
        return response()->json($reservations, 200);
    }

    public function show($id): JsonResponse {
        $reservation = Reservation::where('reserv_id', $id)->first();
        return response()->json($reservation, 200);
    }

    public function store(Request $request): JsonResponse {
        
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'space_id' => 'required',
                'reserv_name' => 'required|string|max:255',
                'reserv_start' => 'required|date',
                'reserv_end' => 'required|date|after:reserv_start',
                'status' => 'boolean' 
            ]);

            if($validator->fails()) throw new ValidationException($validator);

            $reservation = $validator->validated();

            Reservation::create([
                'user_id' => $reservation['user_id'],
                'space_id' => $reservation['space_id'],
                'reserv_name' => $reservation['reserv_name'],
                'reserv_start' => $reservation['reserv_start'],
                'reserv_end' => $reservation['reserv_end']
            ]);

            return response()->json('La reserva fue creada exitosamente.', 200);
            
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'No se pudo crear la reserva.',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}