<?php

namespace Tests\Unit;

use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\SlotMachine;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SlotMachineTest extends TestCase
{
    use RefreshDatabase; // If your test interacts with the database

    public function test_spin_result_is_valid()
    {
        // mock auth user
        $user = User::factory()->create();
        Auth::shouldReceive('user')->andReturn($user);
        
        $component = Livewire::test(SlotMachine::class);
    
        $component->assertCount('reels', $component->get('numberOfReels'));
    }

    public function test_cash_out() {

        // mock auth user
        $user = User::factory()->create();
        Auth::shouldReceive('user')->andReturn($user);
        Auth::shouldReceive('logout')->once();
        
        $component = Livewire::test(SlotMachine::class);
        $component->set('credit', 5);
        
        $component->call('cashOut', true);

        $this->assertGreaterThanOrEqual($component->get('credit'), $user->credits); 
    }

    public function test_probability_over_40_credits()
    {
        $margin = 0.10;

        // mock auth user
        $user = User::factory()->create();
        Auth::shouldReceive('user')->andReturn($user);
        
        $component = Livewire::test(SlotMachine::class);
        $component->set('spinDelay', 0); // turn off delay

        // Define the count of increases
        $numberOfRespins = 0;
        $timesToRun = 250;

        // Run the method 100 times
        for ($i = 0; $i < $timesToRun; $i++) {

            $component->set('credit', 50);

            $component->call('spin', true);
 
            // Check if the property value has increased
            if ($component->get('respun')) {
                $numberOfRespins++;
            }
        }

        // Perform assertion with 5% margin
        $probability = $numberOfRespins / $timesToRun; 
        
        $this->assertGreaterThanOrEqual(0.30 - $margin, $probability); 
        $this->assertLessThanOrEqual(0.30 + $margin, $probability);      
    }

    public function test_probability_over_60_credits()
    {
        $margin = 0.10;

        // mock auth user
        $user = User::factory()->create();
        Auth::shouldReceive('user')->andReturn($user);
        
        $component = Livewire::test(SlotMachine::class);
        $component->set('spinDelay', 0); // turn off delay

        // Define the count of increases
        $numberOfRespins = 0;
        $timesToRun = 250;

        // Run the method 100 times
        for ($i = 0; $i < $timesToRun; $i++) {

            $component->set('credit', 70);
            $component->call('spin', true);
 
            // Check if the property value has increased
            if ($component->get('respun')) {
                $numberOfRespins++;
            }
        }

        // Perform assertion with 5% margin
        $probability = $numberOfRespins / $timesToRun; 
        
        $this->assertGreaterThanOrEqual(0.60 - $margin, $probability); 
        $this->assertLessThanOrEqual(0.60 + $margin, $probability);    
    }
}
