<?php

namespace App\Http\Controllers\Manag;

use App\Hadiah;
use App\Http\Controllers\Controller;
use App\TukarHadiah;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class HadiahController extends Controller
{
    public function index()
    {
        $data = Hadiah::paginate(6);
        $total= Hadiah::count();
        $penukaran_hadiah = TukarHadiah::with('user', 'hadiah')->orderBy('status', 'ASC')->paginate(10);
        $penukaran_hadiah_suksess = TukarHadiah::where('status', 'sukses')->count();
        $penukaran_hadiah_pending = TukarHadiah::where('status', 'pending')->count();
        return view('manag.point.index', [
            'items' => $data,
            'total_hadiah' => $total,
            'tukar_hadiah' => $penukaran_hadiah,
            'CpenukaranS' => $penukaran_hadiah_suksess,
            'CpenukaranP' => $penukaran_hadiah_pending,
        ]);

    }



    public function create()
    {
        return view('manag.point.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            // note, jumlah_point, 'image;
            'note' => 'required|string',
            'jumlah_point' => 'required|numeric',
            'image' => 'required'
        ]);

        $data = $request->all();
        $image = $request->file('image')->store(
            'assets/hadiah', 'public');
            // dd($data);
        $data['image'] = url('storage/'.$image);
        // dd($data);
        $hadiah = Hadiah::create($data);

        return redirect()->route('index-hadiah')->with('toast_success', 'data hadiah berhasil di tambahkan');
    }

    public function edit($id)
    {
        $data = Hadiah::findOrFail($id);

        return view('manag.point.edit', [
            'item' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
      $hadiah = Hadiah::findOrFail($id);
      if($request->file('image'))
      {
          $image = $request->file('image')->store(
            'assets/hadiah', 'public');
            // dd($data);
        $data['image'] = url('storage/'.$image);
      }
    //   dd($data);
    $hadiah->fill($data);
    $hadiah->save();

    return redirect()->route('index-hadiah')->with('toast_success', 'data hadiah berhasil di update');
    }

    public function delete($id)
    {
        $data = Hadiah::find($id);

        $data->delete();

        return redirect()->route('index-hadiah')->with('toast_success', 'data hadiah berhasil di hapus');
    }

    public function hendleSuksessHadiah($id)
    {
        $hadiah = TukarHadiah::findOrFail($id);
        $status = 'sukses';

        $hadiah->update([
            'status' => $status
        ]);
        return redirect()->route('index-hadiah')->with('toast_success', 'data hadiah berhasil di update');
    }
}
