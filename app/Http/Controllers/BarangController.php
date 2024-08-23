<?php

namespace App\Http\Controllers;

use App\Http\Requests\BarangCreateRequest;
use App\Http\Requests\BarangUpdateRequest;
use App\Http\Resources\BarangResource;
use App\Http\Resources\MutasiResource;
use App\Http\Resources\UserResource;
use App\Models\Barang;
use App\Models\Mutasi;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BarangController extends Controller
{
    private function getBarang(User $user, int $idBarang): Barang
    {
        $barang = Barang::where('user_id', $user->id)->where('id', $idBarang)->first();
        if (!$barang) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }

        return $barang;
    }

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

        $result = Barang::with('users')->where('id', $barang->id)->get();
        //dd($result);

        return (BarangResource::collection($result))->response()->setStatusCode(201);
    }

    public function get(int $id): JsonResponse
    {
        $user = Auth::user();

        $barang = $this->getBarang($user, $id);
        $result = Barang::with('users')->where('id', $barang->id)->get();
        //dd($result);

        return (BarangResource::collection($result))->response()->setStatusCode(200);
    }

    public function update(int $id, BarangUpdateRequest $request): JsonResponse
    {
        $user = Auth::user();

        $barang = $this->getBarang($user, $id);

        $data = $request->validated();
        $barang->fill($data);
        $barang->save();

        $result = Barang::with('users')->where('id', $barang->id)->get();
        //dd($result);

        return (BarangResource::collection($result))->response()->setStatusCode(200);

    }

    public function delete(int $id): JsonResponse
    {
        $user = Auth::user();

        $barang = $this->getBarang($user, $id);
        $barang->delete();

        return response()->json([
            'data' => true
        ])->setStatusCode(200);
    }

    public function all(): JsonResponse
    {
        $data = Barang::with('users')->get();
        return (BarangResource::collection($data))->response()->setStatusCode(200);
    }
}
