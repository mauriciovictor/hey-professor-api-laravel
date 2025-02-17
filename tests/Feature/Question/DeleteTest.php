<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, assertDatabaseMissing, deleteJson};

test('should be able to delete a quesiton', function () {
    $user = User::factory()->create();

    $question = $user->questions()->create([
        'question' => 'Question?',
        'status'   => 'draft',
    ]);

    Sanctum::actingAs($user);

    deleteJson(
        route(
            'questions.delete',
            ['question' => $question->id]
        )
    )
        ->assertNoContent();

    assertDatabaseMissing('questions', [
        'id' => $question->id,
    ]);
});

it('should allow that only the creator can delete', function () {
    $user = User::factory()->create();

    $user2 = User::factory()->create();

    $question = $user->questions()->create([
        'question' => 'Question?',
        'status'   => 'draft',
    ]);

    Sanctum::actingAs($user2);

    deleteJson(
        route(
            'questions.delete',
            ['question' => $question->id]
        )
    )
        ->assertForbidden();

    assertDatabaseHas('questions', [
        'id' => $question->id,
    ]);
});
