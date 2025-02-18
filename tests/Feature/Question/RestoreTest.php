<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertNotSoftDeleted, assertSoftDeleted, putJson};

test('should be able to restore a quesiton', function () {
    $user = User::factory()->create();

    $question = $user->questions()->create([
        'question' => 'Question?',
        'status'   => 'draft',
    ]);

    $question->delete();

    assertSoftDeleted('questions', [
        'id' => $question->id,
    ]);

    Sanctum::actingAs($user);

    putJson(
        route(
            'questions.restore',
            ['question' => $question->id]
        )
    )
        ->assertNoContent();

    assertNotSoftDeleted('questions', [
        'id' => $question->id,
    ]);
});

it('should allow that only the creator can restore', function () {
    $user = User::factory()->create();

    $user2 = User::factory()->create();

    $question = $user->questions()->create([
        'question' => 'Question?',
        'status'   => 'draft',
    ]);

    $question->delete();

    Sanctum::actingAs($user2);

    putJson(
        route(
            'questions.restore',
            ['question' => $question->id]
        )
    )
        ->assertForbidden();

    assertSoftDeleted('questions', [
        'id' => $question->id,
    ]);
});

it('should only restore when the question is deleted', function () {
    $user = User::factory()->create();

    $question = $user->questions()->create([
        'question' => 'Question?',
        'status'   => 'draft',
    ]);

    Sanctum::actingAs($user);

    putJson(
        route(
            'questions.restore',
            ['question' => $question->id]
        )
    )
        ->assertNotFound();

    assertNotSoftDeleted('questions', [
        'id' => $question->id,
    ]);
});
