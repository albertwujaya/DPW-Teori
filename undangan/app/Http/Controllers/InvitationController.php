<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rsvp;
use App\Models\Gallery;
use App\Helpers\UndanganHelper;

class InvitationController extends Controller
{
    public function index(Request $request)
    {
        $namaPria   = UndanganHelper::getSetting('nama_pria', 'Albert');
        $namaWanita = UndanganHelper::getSetting('nama_wanita', 'Selviana');
        $tanggalDB  = UndanganHelper::getSetting('tanggal_acara', '2032-08-02');
        $waktuDB    = UndanganHelper::getSetting('waktu_acara', '14:00');
        $lokasiDB   = UndanganHelper::getSetting('lokasi_acara', '123 Anywhere St., Any City, ST 12345');
        $siteTitle  = 'Undangan Pernikahan ' . $namaPria . ' & ' . $namaWanita;

        $namaTamu    = $request->query('nama', 'Tamu Undangan');
        $hariTersisa = UndanganHelper::hitungHariTersisa();
        $tanggalFmt  = UndanganHelper::formatTanggalIndonesia($tanggalDB);
        
        $fotoHero = UndanganHelper::getSetting('foto_hero', '');
        $galeri = Gallery::orderBy('urutan', 'asc')->get();

        return view('index', compact(
            'namaPria', 'namaWanita', 'tanggalDB', 'waktuDB', 'lokasiDB', 
            'siteTitle', 'namaTamu', 'hariTersisa', 'tanggalFmt', 'fotoHero', 'galeri'
        ));
    }

    public function undangan(Request $request)
    {
        $namaPria    = UndanganHelper::getSetting('nama_pria', 'Albert');
        $namaWanita  = UndanganHelper::getSetting('nama_wanita', 'Selviana');
        $tanggalDB   = UndanganHelper::getSetting('tanggal_acara', '2032-08-02');
        $waktuDB     = UndanganHelper::getSetting('waktu_acara', '14:00');
        $lokasiDB    = UndanganHelper::getSetting('lokasi_acara', '123 Anywhere St., Any City, ST 12345');
        $bankName    = UndanganHelper::getSetting('bank_name', 'BCA');
        $noRek       = UndanganHelper::getSetting('nomor_rekening', '123456789');
        $putraDari   = UndanganHelper::getSetting('putra_dari', 'Bapak & Ibu');
        $putriDari   = UndanganHelper::getSetting('putri_dari', 'Bapak & Ibu');
        $dresscode   = UndanganHelper::getSetting('dresscode', 'Pakaian Rapi & Sopan');
        $catatan     = UndanganHelper::getSetting('catatan_tambahan', 'Mohon doa restu agar acara diberkati');

        $atasNama    = $namaPria . ' / ' . $namaWanita;
        $siteTitle   = 'Undangan Pernikahan ' . $namaPria . ' & ' . $namaWanita;
        
        $namaTamu    = 'Tamu Undangan';
        $guestSlug   = $request->query('guest', '');

        if ($guestSlug !== '') {
            $rsvp = Rsvp::where('slug', $guestSlug)->first();
            if ($rsvp && !empty($rsvp->nama)) {
                $namaTamu = $rsvp->nama;
            } elseif ($request->has('nama')) {
                $namaTamu = $request->query('nama');
            }
        } elseif ($request->has('nama')) {
            $namaTamu = $request->query('nama');
        }

        $hariTersisa = UndanganHelper::hitungHariTersisa();
        $tanggalFmt  = UndanganHelper::formatTanggalIndonesia($tanggalDB);
        $salam       = UndanganHelper::salamWaktu();

        $jadwal = [
            ['nama' => 'Akad Nikah', 'waktu' => '14.00 - 15.30 WIB', 'lokasi' => $lokasiDB],
            ['nama' => 'Resepsi',    'waktu' => '16.00 - Selesai WIB', 'lokasi' => $lokasiDB],
        ];

        $fotoPria = UndanganHelper::getSetting('foto_pria', '');
        $fotoWanita = UndanganHelper::getSetting('foto_wanita', '');
        $fotoHero = UndanganHelper::getSetting('foto_hero', '');
        $galeri = Gallery::orderBy('urutan', 'asc')->get();

        return view('undangan', compact(
            'namaPria', 'namaWanita', 'tanggalDB', 'waktuDB', 'lokasiDB', 'bankName', 'noRek',
            'putraDari', 'putriDari', 'dresscode', 'catatan', 'atasNama', 'siteTitle',
            'namaTamu', 'hariTersisa', 'tanggalFmt', 'salam', 'jadwal',
            'fotoPria', 'fotoWanita', 'fotoHero', 'galeri'
        ));
    }

    public function submitRsvp(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'attendance' => 'required|in:ya,tidak',
            'guests' => 'nullable|integer|min:1|max:10',
            'notes' => 'nullable|string'
        ]);

        try {
            $name = trim($request->name);
            $baseSlug = \Str::slug($name);
            if (empty($baseSlug)) {
                $baseSlug = 'tamu-undangan';
            } else {
                $baseSlug = substr($baseSlug, 0, 64);
            }
            
            $slug = $baseSlug . '-' . substr(bin2hex(random_bytes(4)), 0, 8);
            while (Rsvp::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . substr(bin2hex(random_bytes(4)), 0, 8);
            }

            Rsvp::create([
                'nama' => $name,
                'slug' => $slug,
                'kehadiran' => $request->attendance === 'ya' ? 'hadir' : 'tidak',
                'jumlah_tamu' => $request->guests ?? 1,
                'ucapan' => $request->notes,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Konfirmasi kehadiran berhasil dikirim.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data.'
            ]);
        }
    }
}
