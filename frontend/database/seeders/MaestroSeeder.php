<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaestroReference;

class MaestroSeeder extends Seeder
{
    public function run(): void
    {
        $references = [
            // Panji
            [
                'karakter' => 'panji',
                'gerakan_name' => 'Sembahan Awal',
                'description' => 'Gerakan penghormatan di awal tarian Panji. Posisi duduk dengan tangan di depan dada.',
                'difficulty' => 'mudah',
            ],
            [
                'karakter' => 'panji',
                'gerakan_name' => 'Nindak',
                'description' => 'Gerakan berjalan khas karakter Panji yang lemah lembut.',
                'difficulty' => 'menengah',
            ],
            [
                'karakter' => 'panji',
                'gerakan_name' => 'Ngigel',
                'description' => 'Gerakan tangan berputar dengan keluwesan.',
                'difficulty' => 'menengah',
            ],
            
            // Samba
            [
                'karakter' => 'samba',
                'gerakan_name' => 'Sembahan Samba',
                'description' => 'Gerakan penghormatan karakter Samba yang jenaka.',
                'difficulty' => 'mudah',
            ],
            [
                'karakter' => 'samba',
                'gerakan_name' => 'Gerak Lucu',
                'description' => 'Gerakan khas Samba yang mengandung unsur humor.',
                'difficulty' => 'menengah',
            ],
            
            // Rumyang
            [
                'karakter' => 'rumyang',
                'gerakan_name' => 'Sembahan Rumyang',
                'description' => 'Gerakan pembuka karakter Rumyang yang anggun.',
                'difficulty' => 'mudah',
            ],
            [
                'karakter' => 'rumyang',
                'gerakan_name' => 'Gerak Mengalir',
                'description' => 'Gerakan mengalir seperti air.',
                'difficulty' => 'sulit',
            ],
            
            // Tumenggung
            [
                'karakter' => 'tumenggung',
                'gerakan_name' => 'Tanjak Tumenggung',
                'description' => 'Posisi dasar karakter Tumenggung yang gagah.',
                'difficulty' => 'menengah',
            ],
            [
                'karakter' => 'tumenggung',
                'gerakan_name' => 'Gerak Tegas',
                'description' => 'Gerakan tegas menunjukkan kewibawaan.',
                'difficulty' => 'menengah',
            ],
            
            // Klana
            [
                'karakter' => 'klana',
                'gerakan_name' => 'Tanjak Klana',
                'description' => 'Posisi dasar karakter Klana yang dinamis.',
                'difficulty' => 'menengah',
            ],
            [
                'karakter' => 'klana',
                'gerakan_name' => 'Gerak Cepat',
                'description' => 'Gerakan cepat dan dinamis khas Klana.',
                'difficulty' => 'sulit',
            ],
            [
                'karakter' => 'klana',
                'gerakan_name' => 'Gerak Marah',
                'description' => 'Ekspresi kemarahan dalam gerakan.',
                'difficulty' => 'sulit',
            ],
        ];

        foreach ($references as $ref) {
            MaestroReference::create($ref);
        }
    }
}
