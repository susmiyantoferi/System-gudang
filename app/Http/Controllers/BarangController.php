<?php

namespace App\Http\Controllers;

use App\Http\Requests\BarangCreateRequest;
use App\Http\Requests\BarangUpdateRequest;
use App\Http\Resources\BarangResource;
use App\Http\Resources\UserResource;
use App\Models\Barang;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BarangController extends Controller
{
    public function create(BarangCreateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = Auth::user();

        $barang = new Barang($data);
        $barang->user_id = $user->id;

        $kode = Str::uuid()->toString();
        $barang->kode = $kode;
        //dd($barang);
        $barang->save();

        return (new BarangResource($barang))->response()->setStatusCode(201);
    }

    public function get(int $id): BarangResource
    {
        $user = Auth::user();

        $barang = Barang::where('id', $id)->where('user_id', $user->id)->first();
        if (!$barang) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }
        //dd($barang);

        return new BarangResource($barang);
    }

    public function update(int $id, BarangUpdateRequest $request): BarangResource
    {
        $user = Auth::user();

        $barang = Barang::where('id', $id)->where('user_id', $user->id)->first();
        if (!$barang) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }

        $data = $request->validated();
        $barang->fill($data);
        $barang->save();

        return new BarangResource($barang);

    }

    public function delete(int $id): JsonResponse
    {
        $user = Auth::user();

        $barang = Barang::where('id', $id)->where('user_id', $user->id)->first();
        if (!$barang) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }
        $barang->delete();

        return response()->json([
            'data' => true
        ])->setStatusCode(200);
    }


}
