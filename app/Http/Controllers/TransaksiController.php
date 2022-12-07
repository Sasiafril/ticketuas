<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Ticket;

class TransaksiController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    //get booking
     public function index()
    {
        $userLogin = auth()->user();
        if($userLogin['role'] !== 'superadmin' && $userLogin['role'] !== 'admin'){
            return response()->json([
                'status' => 404,
                'message' => "Anda bukan admin ataupun superadmin"
            ], 404);
        }
        $Ticket = Transaksi::all();
        return $Ticket;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getById($id)
    {
        $Ticket = Transaksi::find($id);
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCustomer(Request $request, $id)
    {
        $Ticket = Transaksi::find($id);
        if($Ticket){
            $Ticket->nama_event = $request->nama_event ? $request->nama_event : $Ticket->nama_event;
            $Ticket->nama = $request->nama ? $request->nama : $Ticket->nama;
            $Ticket->email = $request->email ? $request->email : $Ticket->email;
            $Ticket->id_ticket = $request->id_ticket ? $request->id_ticket : $Ticket->id_ticket;
            $Ticket->status_pembayaran = $request->status_pembayaran ? $request->status_pembayaran : $Ticket->status_pembayaran;
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


// yg bisa dikurangi stok
    public function pushCustomer(Request $request)
    {
        $list = Ticket::where('nama_event', $request['nama_event'])->first();
        if($list){
            // aritmatika pengurangan stok
            $list->stok = $list->stok - $request['total_tiket'];
            $list->save();

            $table = Transaksi::create([
                "nama_event" => $request->nama_event,
                "nama" => $request->nama,
                "email" => $request->email,
                "nomor" => $request->nomor_telp,
                "id_ticket" => $list['id'],
                "seat" => $request->seat,
                "status_pembayaran" => $request->status_pembayaran,
                "total_tiket" => $request->total_tiket,
                "total_harga" => $list->harga * $request->total_tiket,
            ]);

            return response()->json([
                'status' => 200,
                'data' => $table
            ],200);

        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Gagal Booking'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteCustomer($id)
    {
        $userLogin = auth()->user();
        if($userLogin['role'] !== 'superadmin' && $userLogin['role'] !== 'admin'){
            return response()->json([
                'status' => 404,
                'message' => "Anda bukan admin ataupun superadmin"
            ], 404);
        }
        $Ticket = Transaksi::where('id', $id)->first();
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
