<?php

namespace App\Http\Controllers;

use App\Http\Requests\MutasiCreateRequset;
use App\Http\Resources\MutasiResource;
use App\Models\Barang;
use App\Models\Mutasi;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MutasiController extends Controller
{
    public function create(int $idBarang, MutasiCreateRequset $requset): JsonResponse{
        $user = Auth::user();
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

        $data = $requset->validated();

        $mutasi = new Mutasi($data);
        $mutasi->barang_id = $barang->id;

        $mutasi->save();

        return (new MutasiResource($mutasi))->response()->setStatusCode(201);
    }
}
