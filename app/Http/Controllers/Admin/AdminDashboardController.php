<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    public function overview()
    {
        $stats = [
            ['label' => 'Total Students', 'value' => 486, 'delta' => '+8.2%'],
            ['label' => 'Total Teachers', 'value' => 28, 'delta' => '+2 New'],
            ['label' => 'Total Classes', 'value' => 19, 'delta' => '6 Active'],
            ['label' => 'Total Registrations', 'value' => 133, 'delta' => '+21 This Month'],
            ['label' => 'Total Events', 'value' => 14, 'delta' => '3 Upcoming'],
        ];

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentRegistrations' => [
                ['name' => 'Rafa Bintang', 'program' => 'Drum', 'status' => 'pending', 'date' => '2026-04-04'],
                ['name' => 'Naila Putri', 'program' => 'Vocal', 'status' => 'accepted', 'date' => '2026-04-03'],
                ['name' => 'Gavin Lazuardi', 'program' => 'Piano', 'status' => 'pending', 'date' => '2026-04-02'],
                ['name' => 'Cleo Amanda', 'program' => 'Violin', 'status' => 'rejected', 'date' => '2026-04-01'],
            ],
            'recentEvents' => [
                ['title' => 'Creative Drum Workshop', 'date' => '20 Mei 2026'],
                ['title' => 'Student Recital Night', 'date' => '12 Juni 2026'],
                ['title' => 'ROFC Internal Concert', 'date' => '20 Agustus 2026'],
            ],
            'recentPosts' => [
                ['title' => '5 Tips Konsisten Latihan Musik', 'status' => 'published'],
                ['title' => 'Highlight Student Performance', 'status' => 'draft'],
                ['title' => 'Panduan Vocal Pemula', 'status' => 'published'],
            ],
            'newStudents' => [
                ['name' => 'Satria Rizky', 'class' => 'Guitar Intermediate'],
                ['name' => 'Anya Patricia', 'class' => 'Piano Beginner'],
                ['name' => 'Mikhael Owen', 'class' => 'Drum Beginner'],
            ],
        ]);
    }

    public function classes()
    {
        return view('admin.classes', [
            'classes' => [
                ['name' => 'Drum Beginner', 'teacher' => 'Andra Prakoso', 'schedule' => 'Mon & Wed 16:00', 'price' => 'Rp850.000', 'status' => 'active'],
                ['name' => 'Piano Intermediate', 'teacher' => 'Kevin Hartono', 'schedule' => 'Tue & Thu 18:00', 'price' => 'Rp950.000', 'status' => 'active'],
                ['name' => 'Vocal Performance', 'teacher' => 'Nadia Putri', 'schedule' => 'Sat 14:00', 'price' => 'Rp900.000', 'status' => 'inactive'],
            ],
        ]);
    }

    public function teachers()
    {
        return view('admin.teachers', [
            'teachers' => [
                ['name' => 'Andra Prakoso', 'instrument' => 'Drum', 'experience' => '10 Tahun', 'bio' => 'Spesialis groove dan stage performance.'],
                ['name' => 'Nadia Putri', 'instrument' => 'Vocal', 'experience' => '8 Tahun', 'bio' => 'Coach vokal modern dan teknik pernapasan.'],
                ['name' => 'Kevin Hartono', 'instrument' => 'Piano', 'experience' => '9 Tahun', 'bio' => 'Klasik, pop, dan improvisasi kreatif.'],
            ],
        ]);
    }

    public function students()
    {
        return view('admin.students', [
            'students' => [
                ['name' => 'Rafa Bintang', 'age' => 12, 'phone' => '081234567891', 'email' => 'rafa@example.com', 'class' => 'Drum Beginner'],
                ['name' => 'Naila Putri', 'age' => 17, 'phone' => '081234567892', 'email' => 'naila@example.com', 'class' => 'Vocal Performance'],
                ['name' => 'Darren Wijaya', 'age' => 23, 'phone' => '081234567893', 'email' => 'darren@example.com', 'class' => 'Piano Intermediate'],
            ],
        ]);
    }

    public function registrations()
    {
        return view('admin.registrations', [
            'registrations' => [
                ['name' => 'Rafa Bintang', 'program' => 'Drum', 'schedule' => 'Weekday Sore', 'status' => 'pending'],
                ['name' => 'Naila Putri', 'program' => 'Vocal', 'schedule' => 'Weekend Pagi', 'status' => 'accepted'],
                ['name' => 'Cleo Amanda', 'program' => 'Violin', 'schedule' => 'Weekday Malam', 'status' => 'rejected'],
            ],
        ]);
    }

    public function gallery()
    {
        return view('admin.gallery', [
            'items' => [
                ['title' => 'Kelas Drum', 'category' => 'Class Activity', 'type' => 'Photo'],
                ['title' => 'Recital 2025', 'category' => 'Performance', 'type' => 'Video'],
                ['title' => 'Studio Session', 'category' => 'Behind The Scene', 'type' => 'Photo'],
            ],
        ]);
    }

    public function events()
    {
        return view('admin.events', [
            'events' => [
                ['title' => 'Creative Drum Workshop', 'date' => '2026-05-20', 'status' => 'upcoming'],
                ['title' => 'Student Recital Night', 'date' => '2026-06-12', 'status' => 'upcoming'],
                ['title' => 'ROFC Internal Concert', 'date' => '2026-08-20', 'status' => 'planning'],
            ],
        ]);
    }

    public function blog()
    {
        return view('admin.blog', [
            'posts' => [
                ['title' => '5 Tips Konsisten Latihan Musik', 'author' => 'Admin', 'status' => 'published'],
                ['title' => 'Cara Memilih Kelas Musik Anak', 'author' => 'Admin', 'status' => 'draft'],
                ['title' => 'Manfaat Belajar Music Theory', 'author' => 'Staff', 'status' => 'published'],
            ],
        ]);
    }

    public function testimonials()
    {
        return view('admin.testimonials', [
            'testimonials' => [
                ['name' => 'Ibu Rina', 'role' => 'Orang Tua', 'message' => 'Perkembangan anak saya luar biasa dalam 3 bulan.'],
                ['name' => 'Arman', 'role' => 'Siswa Drum', 'message' => 'Belajar di ROFC menyenangkan dan terarah.'],
            ],
        ]);
    }

    public function users()
    {
        return view('admin.users', [
            'users' => [
                ['name' => 'Super Admin', 'email' => 'admin@rofcschoolmusic.com', 'role' => 'admin'],
                ['name' => 'Content Staff', 'email' => 'staff@rofcschoolmusic.com', 'role' => 'staff'],
            ],
        ]);
    }

    public function settings()
    {
        return view('admin.settings', [
            'settings' => [
                'school_name' => 'ROFC School Music',
                'email' => 'hello@rofcschoolmusic.com',
                'whatsapp' => '+62 812-3456-7890',
                'address' => 'Jl. Harmoni Musik No. 25, Jakarta Selatan',
                'footer_text' => 'ROFC School Music - Creative and Professional Music Education',
            ],
        ]);
    }
}
