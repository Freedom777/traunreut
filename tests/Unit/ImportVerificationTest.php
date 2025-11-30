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
        
        // Seed states and cities for import verification
        $this->seed(\Database\Seeders\StateSeeder::class);
        $this->seed(\Database\Seeders\TestCitySeeder::class);
    }
    
    public function test_import_results()
    {
        // Check states count (16 German states)
        $this->assertEquals(16, State::count(), 'States count should be 16');

        // Check cities count (3 valid cities from CSV)
        // Aach (RP), Aach (BW), Traunreut (BY). UnknownCity skipped.
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
