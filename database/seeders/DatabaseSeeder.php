<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\MusicClass;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = collect([
            ['name' => 'Super Admin', 'slug' => 'super_admin', 'description' => 'Akses penuh ke seluruh sistem.'],
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'Operasional akademik dan konten website.'],
            ['name' => 'Finance', 'slug' => 'finance', 'description' => 'Manajemen keuangan sekolah musik.'],
            ['name' => 'Teacher', 'slug' => 'teacher', 'description' => 'Portal pengajar dan akademik.'],
            ['name' => 'Student', 'slug' => 'student', 'description' => 'Portal siswa.'],
        ])->mapWithKeys(function (array $role) {
            $model = Role::query()->updateOrCreate(
                ['slug' => $role['slug']],
                ['name' => $role['name'], 'description' => $role['description']]
            );

            return [$role['slug'] => $model];
        });

        $users = [
            ['name' => 'Super Admin ROFC', 'email' => 'superadmin@rofcschoolmusic.com', 'role' => 'super_admin'],
            ['name' => 'Admin ROFC', 'email' => 'admin@rofcschoolmusic.com', 'role' => 'admin'],
            ['name' => 'Finance ROFC', 'email' => 'finance@rofcschoolmusic.com', 'role' => 'finance'],
            ['name' => 'Teacher ROFC', 'email' => 'teacher@rofcschoolmusic.com', 'role' => 'teacher'],
            ['name' => 'Student ROFC', 'email' => 'student@rofcschoolmusic.com', 'role' => 'student'],
        ];

        foreach ($users as $data) {
            $user = User::query()->updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password123'),
                ]
            );

            $user->roles()->syncWithoutDetaching([$roles[$data['role']]->id]);
        }

        $teacherUser = User::query()->where('email', 'teacher@rofcschoolmusic.com')->first();
        $studentUser = User::query()->where('email', 'student@rofcschoolmusic.com')->first();

        $teacher = Teacher::query()->updateOrCreate(
            ['user_id' => $teacherUser?->id],
            [
                'name' => 'Teacher ROFC',
                'instrument' => 'Drum',
                'bio' => 'Mentor utama kelas drum.',
                'experience' => '8 Tahun',
                'is_active' => true,
            ]
        );

        $class = MusicClass::query()->updateOrCreate(
            ['name' => 'Drum Beginner'],
            [
                'description' => 'Kelas dasar drum untuk pemula',
                'price' => 850000,
                'schedule' => 'Mon & Wed 16:00',
                'teacher_id' => $teacher->id,
                'status' => 'active',
            ]
        );

        $student = Student::query()->updateOrCreate(
            ['user_id' => $studentUser?->id],
            [
                'name' => 'Student ROFC',
                'age' => 16,
                'phone' => '081234567800',
                'email' => 'student@rofcschoolmusic.com',
                'is_active' => true,
            ]
        );

        $student->classes()->syncWithoutDetaching([$class->id]);

        $invoice = Invoice::query()->updateOrCreate(
            ['invoice_number' => 'INV-SEED-0001'],
            [
                'student_id' => $student->id,
                'amount' => 850000,
                'issued_at' => now()->subDays(5),
                'due_at' => now()->addDays(25),
                'status' => 'issued',
            ]
        );

        Payment::query()->updateOrCreate(
            ['invoice_id' => $invoice->id, 'student_id' => $student->id],
            [
                'amount' => 850000,
                'paid_at' => now()->subDay(),
                'method' => 'transfer',
                'status' => 'paid',
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password123'),
            ]
        );
    }
}
