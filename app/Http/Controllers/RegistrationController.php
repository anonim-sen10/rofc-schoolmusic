<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class RegistrationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:120'],
            'nama_panggilan' => ['required', 'string', 'max:80'],
            'jenis_kelamin' => ['required', 'in:laki-laki,perempuan'],
            'tempat_lahir' => ['required', 'string', 'max:120'],
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:today'],
            'kewarganegaraan' => ['required', 'string', 'max:120'],
            'alamat' => ['required', 'string', 'max:2000'],
            'no_hp_siswa' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:120'],

            'nama_ortu' => ['required', 'string', 'max:120'],
            'pekerjaan_ortu' => ['nullable', 'string', 'max:120'],
            'no_hp_ortu' => ['required', 'string', 'max:30'],
            'email_ortu' => ['nullable', 'email', 'max:120'],

            'instrumen' => ['required', 'string', 'max:120'],
            'program_tambahan' => ['nullable', 'array'],
            'program_tambahan.*' => ['string', 'max:120'],

            'hari_pilihan' => ['required', 'array', 'min:1'],
            'hari_pilihan.*' => ['string', 'max:40'],

            'pengalaman' => ['required', 'boolean'],
            'deskripsi_pengalaman' => ['nullable', 'string', 'max:2000'],
        ]);

        $tanggalLahir = Carbon::parse($validated['tanggal_lahir']);

        $payload = [
            'nama_lengkap' => $validated['nama_lengkap'],
            'nama_panggilan' => $validated['nama_panggilan'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'kewarganegaraan' => $validated['kewarganegaraan'],
            'alamat' => $validated['alamat'],
            'no_hp_siswa' => $validated['no_hp_siswa'],
            'email' => $validated['email'],

            'nama_ortu' => $validated['nama_ortu'],
            'pekerjaan_ortu' => $validated['pekerjaan_ortu'] ?? null,
            'no_hp_ortu' => $validated['no_hp_ortu'],
            'email_ortu' => $validated['email_ortu'] ?? null,

            'instrumen' => $validated['instrumen'],
            'program_tambahan' => $validated['program_tambahan'] ?? [],
            'hari_pilihan' => $validated['hari_pilihan'],

            'pengalaman' => (bool) $validated['pengalaman'],
            'deskripsi_pengalaman' => $validated['deskripsi_pengalaman'] ?? null,

            // Backward compatibility for existing admin modules using legacy columns.
            'full_name' => $validated['nama_lengkap'],
            'age' => $tanggalLahir->age,
            'phone' => $validated['no_hp_siswa'],
            'preferred_schedule' => implode(', ', $validated['hari_pilihan']),
            'notes' => $validated['deskripsi_pengalaman'] ?? null,
            'status' => 'pending',
        ];

        $existingColumns = Schema::getColumnListing('registrations');
        $filteredPayload = array_intersect_key($payload, array_flip($existingColumns));

        // If new columns are not migrated yet, preserve extra details in legacy notes.
        if (! in_array('nama_lengkap', $existingColumns, true)) {
            $extraNotes = [
                'Nama Panggilan: '.$validated['nama_panggilan'],
                'Jenis Kelamin: '.$validated['jenis_kelamin'],
                'Tempat/Tanggal Lahir: '.$validated['tempat_lahir'].', '.$validated['tanggal_lahir'],
                'Kewarganegaraan: '.$validated['kewarganegaraan'],
                'Alamat: '.$validated['alamat'],
                'Nama Ortu: '.$validated['nama_ortu'],
                'Pekerjaan Ortu: '.($validated['pekerjaan_ortu'] ?? '-'),
                'No HP Ortu: '.$validated['no_hp_ortu'],
                'Email Ortu: '.($validated['email_ortu'] ?? '-'),
                'Instrumen: '.$validated['instrumen'],
                'Program Tambahan: '.implode(', ', $validated['program_tambahan'] ?? []),
                'Hari Pilihan: '.implode(', ', $validated['hari_pilihan']),
                'Pengalaman: '.((bool) $validated['pengalaman'] ? 'Ya' : 'Tidak'),
            ];

            if (! empty($validated['deskripsi_pengalaman'])) {
                $extraNotes[] = 'Deskripsi Pengalaman: '.$validated['deskripsi_pengalaman'];
            }

            $legacyNotes = trim(implode("\n", $extraNotes));
            $baseNotes = (string) ($filteredPayload['notes'] ?? '');
            $filteredPayload['notes'] = trim($baseNotes.($baseNotes !== '' ? "\n\n" : '').$legacyNotes);
        }

        Registration::create($filteredPayload);

        return back()->with('success', 'Pendaftaran Anda berhasil dikirim. Kami akan menghubungi Anda untuk proses berikutnya.');
    }
}
