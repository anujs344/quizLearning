<?php

namespace Tests\Feature;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionRangeScoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_with_integer_values_and_valid_non_overlapping_rules_succeeds()
    {
        $user = User::factory()->create(['role' => 'questioner']);
        $quiz = Quiz::create([ 'created_by' => $user->id, 'title' => 'T', 'description' => '', 'total_questions' => 0, 'is_active' => 1 ]);

        $payload = [
            'text' => 'Q1',
            'time_limit_seconds' => 30,
            'base_points' => 5,
            'options' => [
                ['label' => 'A', 'text' => 'o1', 'points' => 0, 'is_correct' => false],
                ['label' => 'B', 'text' => 'o2', 'points' => 0, 'is_correct' => false],
                ['label' => 'C', 'text' => 'o3', 'points' => 10, 'is_correct' => true],
                ['label' => 'D', 'text' => 'o4', 'points' => 0, 'is_correct' => false],
            ],
            'rules' => [
                ['min_value' => 5, 'max_value' => 15, 'points' => 10],
                ['min_value' => 16, 'max_value' => 25, 'points' => 20],
                ['min_value' => 26, 'max_value' => 35, 'points' => 30],
                ['min_value' => 36, 'max_value' => 45, 'points' => 40],
            ],
        ];

        $resp = $this->actingAs($user)->post(route('quizzes.questions.store', $quiz), $payload);

        $resp->assertRedirect();
        $this->assertDatabaseHas('questions', ['text' => 'Q1']);
        $this->assertDatabaseCount('scoring_rules', 4);
    }

    public function test_create_with_incorrect_option_count_fails()
    {
        $user = User::factory()->create(['role' => 'questioner']);
        $quiz = Quiz::create([ 'created_by' => $user->id, 'title' => 'T', 'description' => '', 'total_questions' => 0, 'is_active' => 1 ]);

        $payload = [
            'text' => 'Q2',
            'time_limit_seconds' => 30,
            'base_points' => 5,
            'options' => [
                ['label' => 'A', 'text' => 'o1', 'points' => 0, 'is_correct' => false],
                ['label' => 'B', 'text' => 'o2', 'points' => 0, 'is_correct' => false],
                ['label' => 'C', 'text' => 'o3', 'points' => 0, 'is_correct' => true],
            ],
            'rules' => [],
        ];

        $resp = $this->actingAs($user)->post(route('quizzes.questions.store', $quiz), $payload);

        $resp->assertSessionHasErrors();
        $this->assertDatabaseMissing('questions', ['text' => 'Q2']);
    }

    public function test_create_with_overlapping_ranges_fails()
    {
        $user = User::factory()->create(['role' => 'questioner']);
        $quiz = Quiz::create([ 'created_by' => $user->id, 'title' => 'T', 'description' => '', 'total_questions' => 0, 'is_active' => 1 ]);

        $payload = [
            'text' => 'Q3',
            'time_limit_seconds' => 30,
            'base_points' => 5,
            'options' => [
                ['label' => 'A', 'text' => 'o1', 'points' => 0, 'is_correct' => false],
                ['label' => 'B', 'text' => 'o2', 'points' => 0, 'is_correct' => false],
                ['label' => 'C', 'text' => 'o3', 'points' => 0, 'is_correct' => true],
                ['label' => 'D', 'text' => 'o4', 'points' => 0, 'is_correct' => false],
            ],
            'rules' => [
                ['min_value' => 5, 'max_value' => 25, 'points' => 10],
                ['min_value' => 20, 'max_value' => 35, 'points' => 20],
                ['min_value' => 36, 'max_value' => 45, 'points' => 30],
                ['min_value' => 46, 'max_value' => 55, 'points' => 40],
            ],
        ];

        $resp = $this->actingAs($user)->post(route('quizzes.questions.store', $quiz), $payload);

        $resp->assertSessionHasErrors();
        $this->assertDatabaseMissing('questions', ['text' => 'Q3']);
    }

    public function test_create_with_incorrect_rule_count_fails()
    {
        $user = User::factory()->create(['role' => 'questioner']);
        $quiz = Quiz::create([ 'created_by' => $user->id, 'title' => 'T', 'description' => '', 'total_questions' => 0, 'is_active' => 1 ]);

        $payload = [
            'text' => 'Q4',
            'time_limit_seconds' => 30,
            'base_points' => 5,
            'options' => [
                ['label' => 'A', 'text' => 'o1', 'points' => 0, 'is_correct' => false],
                ['label' => 'B', 'text' => 'o2', 'points' => 0, 'is_correct' => false],
                ['label' => 'C', 'text' => 'o3', 'points' => 0, 'is_correct' => true],
                ['label' => 'D', 'text' => 'o4', 'points' => 0, 'is_correct' => false],
            ],
            'rules' => [
                ['min_value' => 5, 'max_value' => 15, 'points' => 10],
                // only 1 rule provided; controller expects 4
            ],
        ];

        $resp = $this->actingAs($user)->post(route('quizzes.questions.store', $quiz), $payload);

        $resp->assertSessionHasErrors();
        $this->assertDatabaseMissing('questions', ['text' => 'Q4']);
    }
}
