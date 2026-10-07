<?php

namespace Tests\Feature;

use App\Models\Habit;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class HabitIdorTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_cannot_delete_another_users_habit()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $habit = $userA->habits()->create([
            'name' => 'Membaca Buku',
        ]);

        // Attacker B tries to delete A's habit
        $response = $this->actingAs($userB)->deleteJson("/api/habits/{$habit->id}");

        $response->assertStatus(404);
        
        $this->assertDatabaseHas('habits', [
            'id' => $habit->id,
        ]);
    }

    public function test_user_cannot_toggle_another_users_habit_status()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $habit = $userA->habits()->create([
            'name' => 'Test Toggle Habit',
            'status' => 'pending',
        ]);

        // Attacker B tries to toggle A's habit status
        $response = $this->actingAs($userB)->putJson("/api/habits/{$habit->id}/toggle");
        
        // Handle in case route is PATCH instead of PUT
        if ($response->status() === 405) {
            $response = $this->actingAs($userB)->patchJson("/api/habits/{$habit->id}/toggle");
        }

        $response->assertStatus(404);

        $this->assertDatabaseHas('habits', [
            'id' => $habit->id,
            'status' => 'pending',
        ]);
    }
}
