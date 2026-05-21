<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rsvp;
use App\Models\Pengaturan;
use App\Models\Gallery;
use App\Helpers\UndanganHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DashboardController extends Controller
{
    public function index()
    {
        $namaPria   = UndanganHelper::getSetting('nama_pria', 'Albert');
        $namaWanita = UndanganHelper::getSetting('nama_wanita', 'Selviana');
        $adminUser  = session('admin_user', 'Admin');
        $initials   = strtoupper(substr($adminUser, 0, 2));

        return view('dashboard.index', compact('namaPria', 'namaWanita', 'adminUser', 'initials'));
    }

    public function stats()
    {
        $total   = Rsvp::count();
        $hadir   = Rsvp::where('kehadiran', 'hadir')->count();
        $tidak   = Rsvp::where('kehadiran', 'tidak')->count();
        $pending = Rsvp::where('kehadiran', 'pending')->count();
        $jumlahTotal = Rsvp::where('kehadiran', 'hadir')->sum('jumlah_tamu');

        return response()->json([
            'success'      => true,
            'total'        => $total,
            'hadir'        => $hadir,
            'tidak'        => $tidak,
            'pending'      => $pending,
            'jumlah_total' => (int)$jumlahTotal,
        ]);
    }

    public function listGuests(Request $request)
    {
        $filter = $request->query('filter', 'semua');
        $query = Rsvp::query();
        
        if (in_array($filter, ['hadir', 'tidak', 'pending'])) {
            $query->where('kehadiran', $filter);
        }
        
        $rows = $query->orderBy('created_at', 'desc')->get();
        return response()->json(['success' => true, 'data' => $rows, 'total' => count($rows)]);
    }

    public function deleteGuest(Request $request)
    {
        $id = $request->input('id');
        Rsvp::where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    public function addGuest(Request $request)
    {
        $nama = trim(strip_tags($request->input('nama', '')));
        if ($nama === '') {
            return response()->json(['success' => false, 'message' => 'Nama tamu tidak boleh kosong.']);
        }

        try {
            $baseSlug = \Str::slug($nama);
            if (empty($baseSlug)) {
                $baseSlug = 'tamu-undangan';
            } else {
                $baseSlug = substr($baseSlug, 0, 64);
            }
            
            $slug = $baseSlug . '-' . substr(bin2hex(random_bytes(4)), 0, 8);
            while (Rsvp::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . substr(bin2hex(random_bytes(4)), 0, 8);
            }

            $rsvp = Rsvp::create([
                'nama' => $nama,
                'slug' => $slug,
                'kehadiran' => 'pending',
                'jumlah_tamu' => 1,
                'ucapan' => ''
            ]);
            
            $inviteLink = url('/undangan') . '?guest=' . urlencode($slug);

            return response()->json([
                'success' => true,
                'message' => 'Tamu berhasil ditambahkan.',
                'guest' => [
                    'id' => $rsvp->id,
                    'nama' => $nama,
                    'slug' => $slug,
                    'kehadiran' => 'pending',
                    'jumlah_tamu' => 1,
                    'ucapan' => '',
                    'created_at' => $rsvp->created_at ? $rsvp->created_at->format('Y-m-d H:i:s') : date('Y-m-d H:i:s'),
                    'invite_link' => $inviteLink
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menambahkan tamu: ' . $e->getMessage()]);
        }
    }

    public function editGuest(Request $request)
    {
        $id = $request->input('id');
        $nama = trim(strip_tags($request->input('nama', '')));
        if ($nama === '' || $id <= 0) {
            return response()->json(['success' => false, 'message' => 'Data tidak valid.']);
        }

        try {
            $kehadiran = in_array($request->input('kehadiran'), ['hadir','tidak','pending']) ? $request->input('kehadiran') : 'pending';
            $jumlah = max(1, min(20, (int)$request->input('jumlah_tamu', 1)));
            $ucapan = trim(strip_tags($request->input('ucapan', '')));
            
            Rsvp::where('id', $id)->update([
                'nama' => $nama,
                'kehadiran' => $kehadiran,
                'jumlah_tamu' => $jumlah,
                'ucapan' => $ucapan
            ]);
            
            return response()->json(['success' => true, 'message' => 'Data tamu berhasil diperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui tamu.']);
        }
    }

    public function getSettings()
    {
        $rows = Pengaturan::all();
        $map = [];
        foreach ($rows as $r) {
            $map[$r->key] = $r->value;
        }
        return response()->json(['success' => true, 'data' => $map]);
    }

    public function saveSettings(Request $request)
    {
        $allowed = [
            'nama_pria','nama_wanita','tanggal_acara','waktu_acara','lokasi_acara',
            'bank_name','nomor_rekening','admin_username','admin_password',
            'putra_dari','putri_dari','dresscode','catatan_tambahan',
        ];

        $saved = 0;
        foreach ($allowed as $key) {
            if ($request->has($key)) {
                Pengaturan::updateOrCreate(
                    ['key' => $key],
                    ['value' => trim($request->input($key))]
                );
                $saved++;
            }
        }
        return response()->json(['success' => true, 'saved' => $saved]);
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $key = $request->input('key');
        if (!in_array($key, ['foto_hero', 'foto_pria', 'foto_wanita'])) {
            return response()->json(['success' => false, 'message' => 'Key tidak valid.']);
        }

        $file = $request->file('photo');
        $filename = time() . '_' . $key . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads'), $filename);

        Pengaturan::updateOrCreate(
            ['key' => $key],
            ['value' => 'uploads/' . $filename]
        );

        return response()->json(['success' => true, 'message' => 'Foto berhasil diunggah.', 'path' => 'uploads/' . $filename]);
    }

    public function getGallery()
    {
        $gallery = Gallery::orderBy('urutan', 'asc')->get();
        return response()->json(['success' => true, 'data' => $gallery]);
    }

    public function uploadGallery(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $file = $request->file('photo');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/gallery'), $filename);

        $urutan = Gallery::max('urutan') ?? 0;

        $gallery = Gallery::create([
            'foto' => 'uploads/gallery/' . $filename,
            'urutan' => $urutan + 1
        ]);

        return response()->json(['success' => true, 'message' => 'Foto galeri berhasil ditambahkan.', 'data' => $gallery]);
    }

    public function deleteGallery(Request $request)
    {
        $id = $request->input('id');
        $gallery = Gallery::find($id);

        if ($gallery) {
            $path = public_path($gallery->foto);
            if (File::exists($path)) {
                File::delete($path);
            }
            $gallery->delete();
            return response()->json(['success' => true, 'message' => 'Foto galeri berhasil dihapus.']);
        }

        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.']);
    }
}
