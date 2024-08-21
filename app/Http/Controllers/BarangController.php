<?php

namespace App\Http\Controllers;

use App\Http\Requests\BarangCreateRequest;
use App\Http\Resources\BarangResource;
use App\Http\Resources\UserResource;
use App\Models\Barang;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BarangController extends Controller
{
    public function create(BarangCreateRequest $request): JsonResponse{
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
}
