<?php

namespace App\Http\Controllers\Manag;

use App\Berlangganan;
use App\Http\Controllers\Controller;
use App\Profile;
use App\TransaksiManual;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ManagTransaksiManualController extends Controller
{
    public function index()
    {
        $transaksi_sukses = TransaksiManual::where('status', 'sukses')->with('user', 'kememberan')->orderBy('id', 'DESC')->paginate(10);
        $transaksi_sukses_count = TransaksiManual::where('status', 'sukses')->count();
        $transaksi_pending = TransaksiManual::where('status', 'pending')->with('user', 'kememberan')->orderBy('id', 'DESC')->paginate(8);
        $transaksi_gagal = TransaksiManual::where('status', 'gagal')->with('user', 'kememberan')->orderBy('id', 'DESC')->paginate(8);
        // dd($transaksi_manual);
        $total = TransaksiSukses();
        // dd($total);

        return view('manag.berlangganan.index', [
            'pendapatan' => TransaksiSukses(),
            'transaksi_sukses' => $transaksi_sukses,
            'transaksi_sukses_count' => $transaksi_sukses_count,
            'transaksi_pending' => $transaksi_pending,
            'transaksi_gagal' => $transaksi_gagal,

        ]);
    }

    public function setSuksess($id)
    {
        $transaksi_manual = TransaksiManual::with('kememberan')->find($id);
        $count_transaksi_manual = TransaksiManual::where('status', 'sukses')->count();
        // dd($count_transaksi_manual);
        $double_point = 0;
        if($count_transaksi_manual <= 1000)
        {
            $double_point = $transaksi_manual->kememberan->get_point;
        }

        $transaksi_manual->update([
            'status' => 'sukses'
        ]);
        Berlangganan::create([
            'user_id' => $transaksi_manual->user_id,
            'kememberan_id' => $transaksi_manual->kememberan_id
        ]);

        if($transaksi_manual->referal != null)
        {
            $referal = $transaksi_manual->referal;
            $profile = Profile::where('referal', $referal)->first();
            if(!$profile)
            {
                return redirect()->route('home-transaksi-manual')->with('toast_success', 'Approved Berhasil, referal tidak falid');
            }

            $point = $profile->point + $transaksi_manual->kememberan->get_point + $double_point;
            $profile->update([
                'point' => $point
            ]);

            if($count_transaksi_manual <= 1000)
            {
                return redirect()->route('home-transaksi-manual')->with('toast_success', 'Approved Berhasil, referal falid dan double point');
            }

            return redirect()->route('home-transaksi-manual')->with('toast_success', 'Approved Berhasil, referal falid');
        }

        // toast('Your Post as been submited!','success');
        return redirect()->route('home-transaksi-manual')->with('toast_success', 'Approved Berhasil');
    }

    public function setFailed($id)
    {
        $transaksi_manual = TransaksiManual::find($id);
        $transaksi_manual->update([
            'status' => 'gagal'
        ]);

        return redirect()->route('home-transaksi-manual')->with('toast_success', 'transaki di ubah menjadi gagal');
    }
}
