<?php

namespace App\Http\Controllers;
use App\Models\Room;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoomController extends Controller
{
    public function getAllRooms (Request $request){
    
            $rooms = Room::query()
                ->where('is_active', true|| 1)
                ->get(['user_id','game_id','name','image_url','is_active']);


            return response()->json(
                [
                    "success" => true,
                    "message" => "Get rooms successfully",
                    "data" => $rooms
                ],
                Response::HTTP_OK
            );

    }

    public function getRoomById (Request $request){
        return response("by id");
    }

    public function createRoom (Request $request){
        //1. Recuperamos la información del body.
        $game_id = $request->input('game_id');
        dd($game_id);
        $name = $request->input('name');
        $image_url = $request->input('image_url');
        $is_active = $request->input('is_active');
        //2. Validamos la información
        //3. Guardo los datos.
        $newRoom = Room::create(
        [
        "game_id"->$game_id,
        "name"->$name,
        "image_url"->$image_url,
        "is_active"->$is_active,
        ]
        );
        //4. Enviamos la respuesta.
        return response()->json(
            [
                "success" => true,
                "message" => "room created",
                "data" => $newRoom
            ],
            Response::HTTP_CREATED
        );
    }

    public function updateRoom (Request $request){
        return response("update");
    }

    public function deleteRoomById (Request $request){
        return response("delete room");
    }
}