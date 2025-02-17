<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseMissing, deleteJson};

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
