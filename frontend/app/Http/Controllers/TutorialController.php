<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaestroReference;

class TutorialController extends Controller
{
    public function index()
    {
        $karakters = [
            'panji' => [
                'name' => 'Panji',
                'icon' => '🎭',
                'description' => 'Karakter halus dan lemah lembut'
            ],
            'samba' => [
                'name' => 'Samba', 
                'icon' => '👹',
                'description' => 'Karakter jenaka dan lucu'
            ],
            'rumyang' => [
                'name' => 'Rumyang',
                'icon' => '🌸',
                'description' => 'Karakter anggun dan feminin'
            ],
            'tumenggung' => [
                'name' => 'Tumenggung',
                'icon' => '⚔️',
                'description' => 'Karakter gagah dan berwibawa'
            ],
            'klana' => [
                'name' => 'Klana',
                'icon' => '👺',
                'description' => 'Karakter dinamis dan kuat'
            ]
        ];

        $gerakan = MaestroReference::all()->groupBy('karakter');

        return view('tutorial', compact('karakters', 'gerakan'));
    }

    public function show($karakter, $gerakan)
    {
        $reference = MaestroReference::where('karakter', $karakter)
            ->where('gerakan_name', $gerakan)
            ->firstOrFail();

        return view('tutorial-detail', compact('reference'));
    }
}
