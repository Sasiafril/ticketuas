<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;


class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Ticket = Ticket::all();
        return $Ticket;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        //validasi
        $userLogin = auth()->user();
        if($userLogin['role'] !== 'superadmin' && $userLogin['role'] !== 'admin'){
            return response()->json([
                'status' => 404,
                'message' => "Anda bukan admin ataupun superadmin"
            ], 404);
        }

        // create data
        $table = Ticket::create([
            "nama_event" => $request->nama_event,
            "tanggal" => $request->tanggal,
            "tempat" => $request->tempat,
            "deskripsi" => $request->deskripsi,
            "stok" => $request->stok,
            "harga" => $request->harga
        ]);

        return response()->json([
            'success' => 201,
            'message' => 'Data berhasil disimpan',
            'data' => $table
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //get ticket/id
    public function show($id)
    {
        $Ticket = Ticket::find($id);
        if ($Ticket) {
            return response()->json([
                'status' => 200,
                'data' => $Ticket
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'id atas ' .$id . ' tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //update ticket
    public function update(Request $request, $id)
    {
        $userLogin = auth()->user();
        if($userLogin['role'] !== 'superadmin' && $userLogin['role'] !== 'admin'){
            return response()->json([
                'status' => 404,
                'message' => "Anda bukan admin ataupun superadmin"
            ], 404);
        }
        $Ticket = Ticket::find($id);
        if($Ticket){
            $Ticket->nama_event = $request->nama_event ? $request->nama_event : $Ticket->nama_event;
            $Ticket->tanggal = $request->tanggal ? $request->tanggal : $Ticket->tanggal;
            $Ticket->tempat = $request->tempat ? $request->tempat : $Ticket->tempat;
            $Ticket->deskripsi = $request->deskripsi ? $request->deskripsi : $Ticket->deskripsi;
            $Ticket->stok = $request->stok ? $request->stok : $Ticket->stok;
            $Ticket->harga = $request->harga ? $request->harga : $Ticket->harga;
            $Ticket->save();
            return response()->json([
                'status' => 200,
                'data' => $Ticket
            ],200);

        }else{
            return response()->json([
                'status' => 404,
                'message' => $id . ' tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //Delete Ticket/id
    public function destroy($id)
    {
        $userLogin = auth()->user();
        if($userLogin['role'] !== 'superadmin' && $userLogin['role'] !== 'admin'){
            return response()->json([
                'status' => 404,
                'message' => "Anda bukan admin ataupun superadmin"
            ], 404);
        }
        $Ticket = Ticket::where('id', $id)->first();
        if($Ticket){
            $Ticket->delete();
            return response()->json([
                'status' => 200,
                'data' => $Ticket
            ],200);
        }else{
            return response()->json([
                'status' => 404,
                'message' => $id . ' tidak ditemukan'
            ], 404);
        }
    }
}