<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pitch;
use App\Models\PitchPrice;
use App\Models\Tournament;
use App\Models\Team;
use App\Models\FootballMatch;
use App\Models\Booking;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Manager',
                'password' => Hash::make('password'),
                'role' => 'admin'
            ]
        );

        // Customer user
        $customer = User::firstOrCreate(
            ['email' => 'khachhang@gmail.com'],
            [
                'name' => 'Khách Hàng',
                'password' => Hash::make('password'),
                'role' => 'customer'
            ]
        );

        // 2. Pitches
        $pitchA = Pitch::firstOrCreate(
            ['name' => 'Sân Bóng A (Cỏ nhân tạo 7 người)'],
            [
                'price_per_hour' => 300000,
                'description' => 'Sân cỏ nhân tạo chất lượng cao, có đèn điện chiếu sáng cực tốt, lưới quây mới 100%. Phù hợp đá 7 người.',
                'image' => 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?q=80&w=800&auto=format&fit=crop',
                'is_active' => true
            ]
        );

        $pitchB = Pitch::firstOrCreate(
            ['name' => 'Sân Bóng B (Cỏ tự nhiên 11 người)'],
            [
                'price_per_hour' => 500000,
                'description' => 'Sân cỏ tự nhiên rộng rãi, có khán đài nhỏ. Đạt chuẩn đá tập 11 người hoặc chia lửa.',
                'image' => 'https://plus.unsplash.com/premium_photo-1661963065096-7a42b08fa176?q=80&w=800&auto=format&fit=crop',
                'is_active' => true
            ]
        );

        // 3. Pitch Prices
        PitchPrice::firstOrCreate([
            'pitch_id' => $pitchA->id,
            'start_time' => '17:00:00',
            'end_time' => '20:30:00'
        ], [
            'price_per_hour' => 500000
        ]);

        // 4. Tournaments
        $tournament = Tournament::firstOrCreate(
            ['name' => 'Giải Bóng Đá Phủi Mùa Hè 2026'],
            [
                'start_date' => Carbon::now()->addDays(2),
                'end_date' => Carbon::now()->addDays(16),
                'description' => 'Giải đấu phong trào thường niên dành cho sinh viên và dân văn phòng. Giải thưởng 10.000.000 VNĐ cho đội vô địch.',
                'status' => 'upcoming'
            ]
        );

        // 5. Teams
        $team1 = Team::firstOrCreate(
            ['name' => 'FC Củ Hành', 'tournament_id' => $tournament->id],
            [
                'manager_id' => $customer->id,
                'contact_phone' => '0912123123',
                'status' => 'approved',
                'logo' => 'https://ui-avatars.com/api/?name=F+C&background=random'
            ]
        );

        $team2 = Team::firstOrCreate(
            ['name' => 'Anh Em Cây Khế', 'tournament_id' => $tournament->id],
            [
                'manager_id' => $customer->id,
                'contact_phone' => '0988776655',
                'status' => 'approved',
                'logo' => 'https://ui-avatars.com/api/?name=A+E&background=random'
            ]
        );

        $teamPending = Team::firstOrCreate(
            ['name' => 'Đại Bàng Lửa', 'tournament_id' => $tournament->id],
            [
                'manager_id' => $customer->id,
                'contact_phone' => '0933445566',
                'status' => 'pending',
                'logo' => 'https://ui-avatars.com/api/?name=D+B&background=random'
            ]
        );

        // 6. Matches
        FootballMatch::firstOrCreate([
            'tournament_id' => $tournament->id,
            'home_team_id' => $team1->id,
            'away_team_id' => $team2->id,
        ], [
            'match_time' => Carbon::now()->addDays(3)->setTime(16, 0, 0),
            'home_score' => null,
            'away_score' => null,
            'status' => 'scheduled'
        ]);

        // 7. Bookings
        Booking::firstOrCreate([
            'pitch_id' => $pitchA->id,
            'start_time' => Carbon::now()->copy()->setTime(14, 15, 0),
        ], [
            'user_id' => $customer->id,
            'customer_name' => 'Nguyễn Văn A',
            'customer_phone' => '0123456789',
            'end_time' => Carbon::now()->copy()->setTime(15, 0, 0),
            'total_price' => 300000,
            'status' => 'confirmed'
        ]);

        Booking::firstOrCreate([
            'pitch_id' => $pitchA->id,
            'start_time' => Carbon::now()->addDays(1)->setTime(18, 30, 0),
        ], [
            'user_id' => $customer->id,
            'customer_name' => 'Khách Vãng Lai',
            'customer_phone' => '0999888777',
            'end_time' => Carbon::now()->addDays(1)->setTime(19, 15, 0),
            'total_price' => 500000, // Giá cao điểm
            'status' => 'pending'
        ]);
    }
}
