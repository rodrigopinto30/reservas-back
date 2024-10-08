<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Space;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ReservationController extends Controller
{

    public function index(): JsonResponse
    {
        $reservations = Reservation::latest()->take(5)->get();
        return response()->json($reservations, 200);
    }

    public function show($id): JsonResponse
    {

        $reservation = Reservation::where('id', $id)->first();

        if ($reservation->user_id !== auth()->id()) {
            return response()->json([
                'error' => 'No tiene permiso para acceder a la reserva'
            ], 403);
        }
        return response()->json($reservation, 200);
    }

    public function store(Request $request): JsonResponse
    {

        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'space_id' => 'required',
                'reserv_name' => 'required|string|max:255',
                'reserv_start' => 'required|date',
                'reserv_end' => 'required|date|after:reserv_start',
                'status' => 'boolean'
            ]);

            if ($validator->fails()) throw new ValidationException($validator);

            $validatedData = $validator->validated();

            $space = Space::where('id', $validatedData['space_id'])->first();

            if (!$space) return response()->json('No existe el espacio', 404);

            foreach ($space->reservation as $existingReservation) {
                $newStart = new \DateTime($validatedData['reserv_start']);
                $newEnd = new \DateTime($validatedData['reserv_end']);
                $existingStart = new \DateTime($existingReservation->reserv_start);
                $existingEnd = new \DateTime($existingReservation->reserv_end);

                if (
                    ($newStart >= $existingStart && $newStart <= $existingEnd) ||
                    ($newEnd >= $existingStart && $newEnd <= $existingEnd) ||
                    ($newStart <= $existingStart && $newEnd >= $existingEnd)
                ) {
                    return response()->json('Ya existe una reserva para el horario deseado.', 400);
                }
            }

            Reservation::create([
                'user_id' => $validatedData['user_id'],
                'space_id' => $validatedData['space_id'],
                'reserv_name' => $validatedData['reserv_name'],
                'reserv_start' => $validatedData['reserv_start'],
                'reserv_end' => $validatedData['reserv_end']
            ]);

            return response()->json('La reserva fue creada exitosamente.', 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'No se pudo crear la reserva.',
                'message' => $e->getMessage()
            ], 400);
        }
    }


    public function activeReservations(Request $request): JsonResponse
    {

        try {
            if (!auth()->check()) {
                return response()->json(['error' => 'Usuario no autenticado'], 401);
            }

            $currentTime = now()->format('Y-m-d H:i:s');

            $reservations = Reservation::where('reserv_start', '<=', $currentTime)
                ->where('reserv_end', '>=', $currentTime)
                ->orderBy('reserv_start', 'desc')
                ->take(3)
                ->get();

            if ($reservations->isEmpty()) return response()->json('No hay reservas activas en este momento', 404);

            return response()->json($reservations, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ocurrio un error al obtener las reservas activas.',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function finishedReservations(): JsonResponse
    {
        try {
            if (!auth()->check()) {
                return response()->json(['error' => 'Usuario no autenticado'], 401);
            }

            $currentTime = now()->format('Y-m-d H:i:s');
            $reservations = Reservation::join('users', 'users.id', 'reservations.user_id')
                ->join('spaces', 'spaces.id', 'reservations.space_id')
                ->select('users.name', 'reservations.reserv_name', 'reservations.reserv_start', 'reservations.reserv_end', 'spaces.space_name')
                ->where('reserv_end', '<=', $currentTime)
                ->orderBy('reserv_end', 'asc')
                ->take(3)
                ->get();

            if ($reservations->isEmpty()) return response()->json("No hay reservas finalizadas", 404);

            return response()->json($reservations, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ocurrio un error al obtener las reservas finalizadas',
                'message' => $e->getMessage()
            ]);
        }
    }
}
