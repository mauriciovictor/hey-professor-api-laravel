<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertNotSoftDeleted, assertSoftDeleted, deleteJson};

test('should be able to archive a quesiton', function () {
    $user = User::factory()->create();

    $question = $user->questions()->create([
        'question' => 'Question?',
        'status'   => 'draft',
    ]);

    Sanctum::actingAs($user);

    deleteJson(
        route(
            'questions.archive',
            ['question' => $question->id]
        )
    )
        ->assertNoContent();

    assertSoftDeleted('questions', [
        'id' => $question->id,
    ]);
});

it('should allow that only the creator can archive', function () {
    $user = User::factory()->create();

    $user2 = User::factory()->create();

    $question = $user->questions()->create([
        'question' => 'Question?',
        'status'   => 'draft',
    ]);

    Sanctum::actingAs($user2);

    deleteJson(
        route(
            'questions.archive',
            ['question' => $question->id]
        )
    )
        ->assertForbidden();

    assertNotSoftDeleted('questions', [
        'id' => $question->id,
    ]);
});
