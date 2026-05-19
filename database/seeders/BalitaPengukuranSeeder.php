<?php

namespace Database\Seeders;

use App\Models\Balita;
use App\Models\Pengukuran;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Helpers\WhoHelper;

class BalitaPengukuranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Pastikan ada minimal 1 user yang akan menjadi pengukur
        $user = User::first();

        // if (! $user) {
        //     $user = User::create([
        //         'nama'     => 'Admin Posyandu',
        //         'username' => 'admin',
        //         'email'    => 'admin@example.com',
        //         'password' => Hash::make('password'), // ganti nanti
        //         'role'     => 'admin',
        //     ]);
        // }

        // Buat 20 balita
        for ($i = 0; $i < 20; $i++) {

            $tanggalLahir = Carbon::instance(
                $faker->dateTimeBetween('-5 years', '-1 year')
            )->startOfDay();

            $balita = Balita::create([
                'nama'          => $faker->firstName,
                'tanggal_lahir' => $tanggalLahir->toDateString(),
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'nama_ibu'      => $faker->name('female'),
            ]);

            // 🎯 tentukan tipe anak (20% stunting)
            $type = rand(1, 100) <= 20 ? 'stunting' : 'normal';

            // umur sekarang
            $umurSekarang = rand(12, 59);

            $umurList = [
                $umurSekarang - 2,
                $umurSekarang - 1,
                $umurSekarang
            ];

            $tinggi = null;

            foreach ($umurList as $umur) {

                $tanggalUkur = (clone $tanggalLahir)->addMonths($umur);

                if ($tanggalUkur->greaterThan(now())) {
                    $tanggalUkur = now();
                }

                $median = WhoHelper::getMedian($umur, $balita->jenis_kelamin);

                // =========================
                // TINGGI AWAL
                // =========================
                if ($tinggi === null) {

                    if ($type == 'stunting') {
                        // jauh di bawah median
                        $tinggi = $median - rand(6, 10);
                    } else {
                        // normal sekitar median
                        $tinggi = $median + rand(-2, 2);
                    }
                } else {

                    $delta = WhoHelper::deltaTinggi($umur, $balita->jenis_kelamin);

                    if ($type == 'stunting') {
                        // pertumbuhan lebih lambat
                        $tinggi += ($delta * rand(60, 80) / 100);
                    } else {
                        // pertumbuhan normal
                        $tinggi += $delta + (rand(-1, 1) * 0.2);
                    }
                }

                // =========================
                // HITUNG STATUS WHO
                // =========================
                $z = WhoHelper::zScore($tinggi, $umur, $balita->jenis_kelamin);
                $status = $z < -2 ? 1 : 0;

                Pengukuran::create([
                    'id_balita'       => $balita->id_balita,
                    'id_user'         => $user->id,
                    'tanggal_ukur'    => $tanggalUkur->toDateString(),
                    'umur_bulan'      => $umur,
                    'bb_kg'           => round($tinggi / 10 + rand(0, 2), 1),
                    'tb_cm'           => round($tinggi, 1),
                    'lila_cm'         => $faker->randomFloat(1, 10, 20),
                    'status_stunting' => $status,
                ]);
            }
        }
    }
}
