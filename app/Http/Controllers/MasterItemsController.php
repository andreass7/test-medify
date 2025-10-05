<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MasterItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MasterItemsController extends Controller
{
    public function index()
    {
        return view('master_items.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;
        $hargamin = $request->hargamin;
        $hargamax = $request->hargamax;

        $data_search = MasterItem::query();

        if (!empty($kode)) $data_search = $data_search->where('kode', $kode);
        if (!empty($nama)) $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');
        if (!empty($hargamin)) $data_search = $data_search->where('harga_beli', '>=', $hargamin)->where('harga_beli', '<=', $hargamax);

        $data_search = $data_search->select('kode', 'nama', 'jenis', 'harga_beli', 'laba', 'supplier', 'avatar')->orderBy('id')->get();

        $data_search->map(function ($item) {
            $item->avatar_url = $item->avatar
                ? asset('storage/' . $item->avatar)
                : asset('storage/default.png');
            return $item;
        });

        return json_encode([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = null)
    {
        if ($method == 'new') {
            $item = new MasterItem();
        } else {
            $item = MasterItem::with('categories')->findOrFail($id);
        }
        return view('master_items.form.index', [
            'item' => $item,
            'method' => $method
        ]);
    }

    public function singleView($kode)
    {
        $data['data'] = MasterItem::where('kode', $kode)->first();
        return view('master_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        if ($method == 'new') {
            $data_item = new MasterItem();
            $kode = MasterItem::count('id') + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
            sleep(1);
        } else {
            $data_item = MasterItem::findOrFail($id);
            $kode = $data_item->kode;
        }

        // Isi data utama
        $data_item->nama = $request->nama;
        $data_item->harga_beli = $request->harga_beli;
        $data_item->laba = $request->laba;
        $data_item->kode = $kode;
        $data_item->supplier = $request->supplier;

        // Ambil nama kategori untuk update 'jenis'
        $kategori_names = Category::whereIn('id', $request->kategori ?? [])->pluck('nama')->toArray();
        $data_item->jenis = implode(', ', $kategori_names);

        // Simpan MasterItem dulu supaya ada ID
        $data_item->save();

        // Sync kategori
        $data_item->categories()->sync($request->kategori ?? []);

        // Upload avatar
        if ($request->hasFile('avatar')) {
            if ($data_item->avatar && file_exists(public_path('storage/' . $data_item->avatar))) {
                unlink(public_path('storage/' . $data_item->avatar));
            }
            $file = $request->file('avatar');
            $path = $file->store('avatars', 'public');
            $data_item->avatar = $path;
            $data_item->save();
        }

        return redirect('master-items')->with('success', 'Item berhasil disimpan.');
    }


    public function delete($id)
    {
        MasterItem::find($id)->delete();
        return redirect('master-items');
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        $avatar_samples = Storage::disk('public')->files('avatars/sample');

        foreach ($data as $item) {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100, 1000000);
            $item->laba = rand(10, 99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->jenis = $this->getRandomJenis();
            if (count($avatar_samples) > 0) {

                $random_avatar = $avatar_samples[array_rand($avatar_samples)];

                $ext = pathinfo($random_avatar, PATHINFO_EXTENSION);
                $new_filename = 'avatars/' . Str::random(10) . '.' . $ext;

                if ($item->avatar && Storage::disk('public')->exists($item->avatar)) {
                    Storage::disk('public')->delete($item->avatar);
                }
                Storage::disk('public')->copy($random_avatar, $new_filename);

                $item->avatar = $new_filename;
            }
            $item->save();
        }
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi', 'Bukulapuk', 'TokoBagas', 'E Commurz', 'Blublu'];
        $random = rand(0, 4);
        return $array[$random];
    }

    private function getRandomJenis()
    {
        $array = ['Obat', 'Alkes', 'Matkes', 'Umum', 'ATK'];
        $random = rand(0, 4);
        return $array[$random];
    }
}
