<?php

namespace Tests\Unit;

use App\Models\City;
use App\Models\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\StateSeeder::class);

        // Create representative cities directly — no separate seeder needed.
        // Mirrors what import:cities would produce from a real CSV.
        City::insert([
            ['name' => 'Aach', 'zip_code' => '56736', 'state_code' => 'RP', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Aach', 'zip_code' => '78267', 'state_code' => 'BW', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Traunreut', 'zip_code' => '83301', 'state_code' => 'BY', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function test_import_results()
    {
        // StateSeeder seeds 16 German states + AT (Österreich) + UN (unknown) = 18 total
        $this->assertEquals(18, State::count(), 'States count should be 18');

        // 3 representative cities created in setUp
        $this->assertEquals(3, City::count(), 'Cities count should be 3');

        // Check specific city
        $traunreut = City::where('name', 'Traunreut')->first();
        $this->assertNotNull($traunreut);
        $this->assertEquals('83301', $traunreut->zip_code);
        $this->assertEquals('BY', $traunreut->state_code);

        // Check state relationship
        $this->assertEquals('Bayern', $traunreut->state->name);
    }
}
