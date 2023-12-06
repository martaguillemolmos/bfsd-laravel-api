<?php

namespace App\Http\Controllers;
use App\Models\Room;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class MemberController extends Controller
{
    public function addUserRoom(Request $request)
    {
        try {
            $userId = auth()->id();
            $room_id = $request->input('room_id');
            $newMember = Member::create([
                "user_id" => $userId,
                "room_id" => $room_id
            ]);

            return response()->json(
                [
                    "success" => true,
                    "message" => "Member created",
                    "data" => $newMember
                ],
                Response::HTTP_CREATED
            );
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            dd($th);
            return response()->json(
                [
                    "success" => false,
                    "message" => "Error creating member"
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function addMember(Request $request)
    {
        try {
            $userId = auth()->id();
            $userCreateRoom = Room::where("user_id", $userId);

            if(!$userCreateRoom){
                return response()->json(
                    [
                        "success" => false,
                        "message" => "Don't have auth"
                    ],
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
            }
            //Recuperamos la infor del body
            $user_id = $request->input('user_id');
            $room_id = $request->input('room_id');

            $addMember = Member::create([
                "user_id" => $user_id,
                "room_id" => $room_id
            ]);

            return response()->json(
                [
                    "success" => true,
                    "message" => "Member created",
                    "data" => $addMember
                ],
                Response::HTTP_CREATED
            );
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json(
                [
                    "success" => false,
                    "message" => "Error creating member"
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }


    public function getAllMembers()
    {
        try {
            $members = Member::query()->get();

            return response()->json(
                [
                    "success" => true,
                    "message" => "Get member successfully",
                    "data" => $members
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json(
                [
                    "success" => false,
                    "message" => "Error getting members"
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function getMemberById(Request $request)
    {
        try {

            $userId = auth()->id();
            $member = Member::where("user_id", $userId)->get();

            return response()->json(
                [
                    "success" => true,
                    "message" => "Get member",
                    "data" => $member
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json(
                [
                    "success" => false,
                    "message" => "Error getting member"
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function deleteMemberById($id)
    {
        try {
            $memberId = Member::query()->find($id);

            if (!$memberId) {
                return response()->json(
                    [
                        "success" => true,
                        "message" => "Member not found"
                    ],
                    Response::HTTP_NOT_FOUND
                );
            }

            $userId = auth()->id();
            if ($memberId->user_id != $userId) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "You are not authorized to delete this member"
                    ],
                    Response::HTTP_UNAUTHORIZED
                );
            }
            $memberDeleted = $memberId->delete();

            if ($memberDeleted) {
                return response()->json(
                    [
                        "success" => true,
                        "message" => "Member deleted successfully"
                    ],
                    Response::HTTP_OK
                );
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json(
                [
                    "success" => false,
                    "message" => "Error deleting member"
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
