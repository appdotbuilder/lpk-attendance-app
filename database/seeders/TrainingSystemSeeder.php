<?php

namespace Database\Seeders;

use App\Models\AttendanceRecord;
use App\Models\ClassEnrollment;
use App\Models\PicketReport;
use App\Models\TrainingClass;
use App\Models\TrainingSchedule;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TrainingSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@lpkbmp.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+62812345678',
            'nik' => '1234567890123456',
            'birth_place' => 'Jakarta',
            'birth_date' => '1985-01-15',
            'gender' => 'male',
            'address' => 'Jakarta, Indonesia',
            'emergency_contact_name' => 'Emergency Contact',
            'emergency_contact_phone' => '+62812345679',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Create instructor users
        $instructor1 = User::create([
            'name' => 'Bu Sari Wijaya',
            'email' => 'sari.wijaya@lpkbmp.com',
            'password' => Hash::make('password'),
            'role' => 'instructor',
            'phone' => '+62813456789',
            'nik' => '2345678901234567',
            'birth_place' => 'Surabaya',
            'birth_date' => '1980-03-20',
            'gender' => 'female',
            'address' => 'Surabaya, Indonesia',
            'emergency_contact_name' => 'Pak Budi Wijaya',
            'emergency_contact_phone' => '+62813456790',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $instructor2 = User::create([
            'name' => 'Pak Ahmad Hidayat',
            'email' => 'ahmad.hidayat@lpkbmp.com',
            'password' => Hash::make('password'),
            'role' => 'instructor',
            'phone' => '+62814567890',
            'nik' => '3456789012345678',
            'birth_place' => 'Bandung',
            'birth_date' => '1982-07-10',
            'gender' => 'male',
            'address' => 'Bandung, Indonesia',
            'emergency_contact_name' => 'Ibu Fatimah Hidayat',
            'emergency_contact_phone' => '+62814567891',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Create demo CPMI user
        $demoUser = User::create([
            'name' => 'Demo CPMI User',
            'email' => 'demo@lpkbmp.com',
            'password' => Hash::make('password'),
            'role' => 'cpmi',
            'phone' => '+62815678901',
            'nik' => '4567890123456789',
            'birth_place' => 'Yogyakarta',
            'birth_date' => '1995-05-15',
            'gender' => 'female',
            'address' => 'Yogyakarta, Indonesia',
            'emergency_contact_name' => 'Ibu Siti',
            'emergency_contact_phone' => '+62815678902',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Create additional CPMI users
        $cpmiUsers = User::factory()->cpmi()->count(25)->create();
        $allCpmi = $cpmiUsers->concat([$demoUser]);

        // Create training classes
        $activeClass = TrainingClass::create([
            'name' => 'Batch 2024-03 Taiwan Preparation',
            'code' => 'TW-2024-03',
            'description' => 'Comprehensive training program for Indonesian Migrant Worker Candidates going to Taiwan. Includes language training, cultural orientation, workplace skills, and safety procedures.',
            'instructor_id' => $instructor1->id,
            'start_date' => now()->subDays(30),
            'end_date' => now()->addDays(60),
            'max_students' => 30,
            'status' => 'active',
            'schedule' => [
                'monday' => ['start' => '08:00', 'end' => '16:00'],
                'tuesday' => ['start' => '08:00', 'end' => '16:00'],
                'wednesday' => ['start' => '08:00', 'end' => '16:00'],
                'thursday' => ['start' => '08:00', 'end' => '16:00'],
                'friday' => ['start' => '08:00', 'end' => '15:00'],
            ],
            'location' => 'LPK Bahana Mega Prestasi Training Center, Jakarta',
        ]);

        $completedClass = TrainingClass::create([
            'name' => 'Batch 2024-01 Taiwan Preparation',
            'code' => 'TW-2024-01',
            'description' => 'Successfully completed training program for Indonesian workers deployed to Taiwan.',
            'instructor_id' => $instructor2->id,
            'start_date' => now()->subDays(150),
            'end_date' => now()->subDays(30),
            'max_students' => 25,
            'status' => 'completed',
            'schedule' => [
                'monday' => ['start' => '08:00', 'end' => '16:00'],
                'tuesday' => ['start' => '08:00', 'end' => '16:00'],
                'wednesday' => ['start' => '08:00', 'end' => '16:00'],
                'thursday' => ['start' => '08:00', 'end' => '16:00'],
                'friday' => ['start' => '08:00', 'end' => '15:00'],
            ],
            'location' => 'LPK Bahana Mega Prestasi Training Center, Jakarta',
        ]);

        // Enroll CPMI users in active class
        $enrolledUsers = $allCpmi->take(20);
        foreach ($enrolledUsers as $user) {
            ClassEnrollment::create([
                'user_id' => $user->id,
                'training_class_id' => $activeClass->id,
                'enrolled_at' => now()->subDays(random_int(1, 30)),
                'status' => 'active',
            ]);
        }

        // Create training schedules for the active class
        $subjects = [
            'Mandarin Language Basics',
            'Taiwan Culture & Customs',
            'Workplace Safety Procedures',
            'Indonesian Labor Law',
            'Communication Skills',
            'Personal Hygiene & Health',
            'Technology & Equipment Usage',
            'Emergency Procedures',
            'Financial Management',
            'Cultural Adaptation',
        ];

        $scheduleTypes = ['theory', 'practical', 'exam', 'orientation'];

        // Create schedules for the next 14 days
        for ($i = 0; $i < 14; $i++) {
            $date = now()->addDays($i);
            
            // Skip weekends
            if ($date->isWeekend()) {
                continue;
            }

            TrainingSchedule::create([
                'training_class_id' => $activeClass->id,
                'subject' => $subjects[array_rand($subjects)],
                'description' => 'Comprehensive training session covering practical skills and theoretical knowledge required for Taiwan deployment.',
                'date' => $date,
                'start_time' => '08:00:00',
                'end_time' => $i % 5 === 4 ? '15:00:00' : '16:00:00', // Friday shorter
                'room' => 'Room ' . random_int(1, 5),
                'materials' => 'Training materials, worksheets, and practical equipment will be provided.',
                'type' => $scheduleTypes[array_rand($scheduleTypes)],
                'is_mandatory' => true,
            ]);
        }

        // Create attendance records for enrolled users
        foreach ($enrolledUsers as $user) {
            // Create attendance for the last 20 days
            for ($i = 20; $i >= 0; $i--) {
                $date = now()->subDays($i);
                
                // Skip weekends
                if ($date->isWeekend()) {
                    continue;
                }

                $isPresent = random_int(1, 100) <= 85; // 85% attendance rate
                $isLate = $isPresent && random_int(1, 100) <= 15; // 15% late when present

                $status = 'absent';
                $checkInTime = null;
                $checkOutTime = null;
                $latitude = null;
                $longitude = null;
                $locationAddress = null;

                if ($isPresent) {
                    $status = $isLate ? 'late' : 'present';
                    $checkInTime = $isLate 
                        ? sprintf('%02d:%02d:00', random_int(8, 9), random_int(31, 59))
                        : sprintf('%02d:%02d:00', random_int(7, 8), random_int(0, 30));
                    $checkOutTime = sprintf('%02d:%02d:00', random_int(15, 16), random_int(0, 59));
                    $latitude = -6.2088 + (random_int(-100, 100) / 10000); // Jakarta area
                    $longitude = 106.8456 + (random_int(-100, 100) / 10000);
                    $locationAddress = 'LPK Bahana Mega Prestasi, Jakarta';
                }

                AttendanceRecord::create([
                    'user_id' => $user->id,
                    'training_class_id' => $activeClass->id,
                    'date' => $date,
                    'check_in_time' => $checkInTime,
                    'check_out_time' => $checkOutTime,
                    'status' => $status,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'location_address' => $locationAddress,
                    'is_valid_location' => $isPresent,
                ]);
            }
        }

        // Create some picket reports
        $reportTemplates = [
            'Today we participated in Mandarin language training focusing on workplace communication. The instructor taught us essential phrases for factory work and daily interactions. All students were actively engaged and practicing pronunciation. The training materials were clear and helpful.',
            'Morning session covered Taiwan workplace culture and etiquette. We learned about proper behavior, respect for supervisors, and teamwork importance. Afternoon was practical training on safety equipment usage. Everyone participated well and asked relevant questions.',
            'Cultural orientation session about Taiwanese customs and traditions. Very informative about dos and don\'ts in Taiwan society. Also learned about local food, transportation, and shopping. Students showed great interest and took detailed notes.',
            'Safety procedures training was conducted with hands-on practice. We learned about emergency exits, fire safety, and workplace accident prevention. The instructor demonstrated proper use of safety equipment. All students passed the practical assessment.',
            'Financial management workshop teaching us about banking in Taiwan, money transfer to Indonesia, and budgeting tips. Very practical information that will help us manage our earnings effectively. Students asked many questions about banking procedures.'
        ];

        foreach ($enrolledUsers->take(10) as $user) {
            // Create reports for the last 7 days
            for ($i = 7; $i >= 1; $i--) {
                $date = now()->subDays($i);
                
                // Skip weekends and random skip some days
                if ($date->isWeekend() || random_int(1, 100) <= 30) {
                    continue;
                }

                PicketReport::create([
                    'user_id' => $user->id,
                    'training_class_id' => $activeClass->id,
                    'date' => $date,
                    'report' => $reportTemplates[array_rand($reportTemplates)],
                    'issues' => random_int(1, 100) <= 20 ? 'Air conditioning in room 3 was too cold. Some students felt uncomfortable during the afternoon session.' : null,
                    'suggestions' => random_int(1, 100) <= 30 ? 'More practical exercises would be beneficial. Perhaps we could have more group activities to practice conversation skills.' : null,
                    'status' => ['submitted', 'reviewed', 'approved'][random_int(0, 2)],
                    'reviewed_by' => random_int(1, 100) <= 70 ? $instructor1->id : null,
                    'reviewed_at' => random_int(1, 100) <= 70 ? $date->addHours(random_int(2, 8)) : null,
                ]);
            }
        }

        $this->command->info('Training system seeded successfully!');
        $this->command->info('Demo accounts created:');
        $this->command->info('Admin: admin@lpkbmp.com / password');
        $this->command->info('Instructor: sari.wijaya@lpkbmp.com / password');
        $this->command->info('CPMI Demo: demo@lpkbmp.com / password');
    }
}