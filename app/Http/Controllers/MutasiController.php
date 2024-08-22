<?php

namespace App\Http\Controllers;

use App\Http\Requests\MutasiCreateRequset;
use App\Http\Requests\MutasiUpdateRequest;
use App\Http\Resources\MutasiAllResource;
use App\Http\Resources\MutasiResource;
use App\Models\Barang;
use App\Models\Mutasi;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MutasiController extends Controller
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

    private function getMutasi(Barang $barang, int $idMutasi): Mutasi
    {
        $mutasi = Mutasi::where('barang_id', $barang->id)->where('id', $idMutasi)->first();

        if (!$mutasi) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }

        return $mutasi;
    }

    public function create(int $idBarang, MutasiCreateRequset $requset): JsonResponse
    {
        $user = Auth::user();
        $barang = $this->getBarang($user, $idBarang);

        $data = $requset->validated();
        $mutasi = new Mutasi($data);
        $mutasi->barang_id = $barang->id;
        $mutasi->user_id = $user->id;
        //dd($mutasi);
        $mutasi->save();

        return (new MutasiResource($mutasi))->response()->setStatusCode(201);
    }

    public function get(int $idBarang, int $idMutasi): JsonResponse
    {
        $user = Auth::user();

        $barang = $this->getBarang($user, $idBarang);
        $mutasi = $this->getMutasi($barang, $idMutasi);

        $result = Mutasi::with(['users', 'barangs'])->where('barang_id', $barang->id)
            ->where('id',$mutasi->id)->get();
        //dd($result);
        return (MutasiResource::collection($result))->response()->setStatusCode(200);
    }

    public function update(int $idBarang, int $idMutasi, MutasiUpdateRequest $request): MutasiResource
    {
        $user = Auth::user();

        $barang = $this->getBarang($user, $idBarang);
        $mutasi = $this->getMutasi($barang, $idMutasi);

        $data = $request->validated();
        $mutasi->fill($data);
        $mutasi->save();

        return new MutasiResource($mutasi);
    }

    public function delete(int $idBarang, int $idMutasi): JsonResponse
    {
        $user = Auth::user();

        $barang = $this->getBarang($user, $idBarang);
        $mutasi = $this->getMutasi($barang, $idMutasi);
        $mutasi->delete();

        return response()->json([
            'data' => true
        ])->setStatusCode(200);
    }

    public function list(int $idBarang): JsonResponse
    {
        $user = Auth::user();
        $barang = $this->getBarang($user, $idBarang);

        $mutasi = Mutasi::with(['users', 'barangs'])->where('barang_id', $barang->id)->get();
        //dd($mutasi);
        return (MutasiResource::collection($mutasi))->response()->setStatusCode(200);
    }

    public function allData(): JsonResponse{

        $mutasi = Mutasi::with(['users', 'barangs'])->get();
        //dd($mutasi);

        return (MutasiResource::collection($mutasi))->response()->setStatusCode(200);
    }
}
