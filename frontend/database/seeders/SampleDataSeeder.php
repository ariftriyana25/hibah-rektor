<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PracticeSession;
use App\Models\Leaderboard;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample users
        $users = [
            [
                'name' => 'Ahmad Santoso',
                'email' => 'ahmad@example.com',
                'password' => Hash::make('password123'),
                'level' => 'Mahir',
                'total_score' => 4500,
                'practice_count' => 45,
            ],
            [
                'name' => 'Siti Rahayu',
                'email' => 'siti@example.com',
                'password' => Hash::make('password123'),
                'level' => 'Master',
                'total_score' => 7200,
                'practice_count' => 78,
            ],
            [
                'name' => 'Budi Prasetyo',
                'email' => 'budi@example.com',
                'password' => Hash::make('password123'),
                'level' => 'Menengah',
                'total_score' => 2100,
                'practice_count' => 25,
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi@example.com',
                'password' => Hash::make('password123'),
                'level' => 'Mahir',
                'total_score' => 5300,
                'practice_count' => 52,
            ],
            [
                'name' => 'Eko Wijaya',
                'email' => 'eko@example.com',
                'password' => Hash::make('password123'),
                'level' => 'Dasar',
                'total_score' => 800,
                'practice_count' => 10,
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);

            // Create practice sessions for each user
            $karakters = ['panji', 'samba', 'rumyang', 'tumenggung', 'klana'];
            
            for ($i = 0; $i < rand(5, 15); $i++) {
                $karakter = $karakters[array_rand($karakters)];
                $wiraga = rand(50, 95);
                $wirama = rand(45, 90);
                $wirasa = rand(40, 85);
                $total = ($wiraga * 0.4) + ($wirama * 0.3) + ($wirasa * 0.3);

                PracticeSession::create([
                    'user_id' => $user->id,
                    'karakter' => $karakter,
                    'wiraga_score' => $wiraga,
                    'wirama_score' => $wirama,
                    'wirasa_score' => $wirasa,
                    'total_score' => round($total, 1),
                    'duration' => rand(180, 600),
                    'feedback' => ['Terus berlatih!'],
                    'created_at' => Carbon::now()->subDays(rand(0, 30)),
                ]);

                // Update leaderboard
                $entry = Leaderboard::firstOrCreate(
                    ['user_id' => $user->id, 'karakter' => $karakter],
                    ['best_score' => 0, 'rank' => 0]
                );

                if ($total > $entry->best_score) {
                    $entry->best_score = $total;
                    $entry->save();
                }
            }
        }

        // Update rankings
        $karakters = ['panji', 'samba', 'rumyang', 'tumenggung', 'klana'];
        foreach ($karakters as $karakter) {
            $entries = Leaderboard::where('karakter', $karakter)
                ->orderBy('best_score', 'desc')
                ->get();

            foreach ($entries as $index => $entry) {
                $entry->rank = $index + 1;
                $entry->save();
            }
        }

        $this->command->info('Sample data seeded successfully!');
    }
}
