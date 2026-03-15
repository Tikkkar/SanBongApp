<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tournament;
use App\Models\Team;
use App\Models\User;

class DemoTournamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if an admin user exists or get the first one
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::first();
            if (!$admin) {
                $this->command->error("Chưa có User nào trong hệ thống để làm quản lý giải đấu!");
                return;
            }
        }

        // Tạo Giải Đấu demo
        $tournament = Tournament::create([
            'name' => 'Giải Bóng Đá Siêu Cúp (Random 8 Vòng)',
            'description' => 'Giải đấu giao hữu ',
            'start_date' => now()->addDays(2)->format('Y-m-d'),
            'end_date' => now()->addDays(30)->format('Y-m-d'),
            'status' => 'upcoming',
        ]);

        $this->command->info("Đã tạo Giải Đấu: {$tournament->name}");

        // Tạo 9 Đội bóng (để có đúng 8 vòng đấu trong thể thức đá vòng tròn do 1 đội nghỉ mỗi vòng)
        $teamsData = [
            'FC Rồng Vàng',
            'Sài Gòn Utd',
            'Hà Nội FC',
            'Đà Nẵng City',
            'Hải Phòng Port',
            'HAGL Academy',
            'Cần Thơ FC',
            'Bình Dương Club',
            'Sông Lam Ngệ An'
        ];

        foreach ($teamsData as $teamName) {
            Team::create([
                'tournament_id' => $tournament->id,
                'manager_id' => $admin->id,
                'name' => $teamName,
                'contact_phone' => '0900000' . rand(100, 999),
                'status' => 'approved',
                // Đội bóng được duyệt ngay lập tức để có thể bốc thăm luôn
            ]);
        }

        $this->command->info("Đã tạo tự động 9 đội bóng vào giải đấu.");
    }
}
