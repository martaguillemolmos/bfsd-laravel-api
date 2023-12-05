<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class GameController extends Controller
{

    public function createGame(Request $request)
    {
        Log::info('Create Game');
        try {
            $userId=auth()->id();
            $name = $request->input('name');
            $description = $request->input('description');
            $image = $request->input('image');
            $newMessage = Game::create([
                "user_id" => $userId,
                'name' => $name,
                "description" => $description,
                "image" => $image
            ]);

            return response()->json(
                [
                    "success" => true,
                    "message" => "Game created",
                    "data" => $newMessage
                ],
                Response::HTTP_CREATED
            );
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            dd($th);
            return response()->json(
                [
                    "success" => false,
                    "message" => "Error creating message"
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function getAllGames(Request $request)
    {
        try {
            $games = Game::query()->get();

            return response()->json(
                [
                    "success" => true,
                    "message" => "Get games successfully",
                    "data" => $games
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json(
                [
                    "success" => false,
                    "message" => "Error gettin games"
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function getGameById(Request $request, $id)
    {
        try {
            $game = Game::find($id)->get();

            return response()->json(
                [
                    "success" => true,
                    "message" => "Get game by user_id",
                    "data" => $game
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json(
                [
                    "success" => false,
                    "message" => "Error getting game with courses"
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function updateGame(Request $request, $id)
{
    try {
        $game = Game::find($id);

        if (!$game) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Game doesn't exist"
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $name = $request->input('name');
        $description = $request->input('description');
        $image = $request->input('image');

        $game->name = $name;
        $game->description = $description;
        $game->image = $image;

        $game->save();

        return response()->json(
            [
                "success" => true,
                "message" => "Game updated",
                "data" => $game
            ],
            Response::HTTP_OK
        );
    } catch (\Throwable $th) {
        Log::error($th->getMessage());

        return response()->json(
            [
                "success" => false,
                "message" => "Error updating game"
            ],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}

}
