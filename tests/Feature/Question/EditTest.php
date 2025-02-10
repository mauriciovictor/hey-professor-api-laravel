

<?php

use App\Models\{Question, User};
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{assertDatabaseHas, putJson};

it('should be able to update a question', function () {
    $user = User::factory()->create();

    $question = Question::factory()->create([
        'user_id' => $user->id,
    ]);

    Sanctum::actingAs($user);

    putJson(route('questions.update', $question), [
        'question' => 'Updating question?',
    ])->assertOk();

    assertDatabaseHas('questions', [
        'id'       => $question->id,
        'question' => 'Updating question?',
    ]);
});

describe('validation rules', function () {
    test('question required ', function () {
        $user = User::factory()->create();

        $question = Question::factory()->create([
            'user_id' => $user->id,
        ]);

        Sanctum::actingAs($user);

        putJson(
            route('questions.update', $question),
            []
        )->assertJsonValidationErrors([
            'question' => 'required',
        ]);
    });

    test('question ending with question mark', function () {
        $user = User::factory()->create();

        $question = Question::factory()->create([
            'user_id' => $user->id,
        ]);

        Sanctum::actingAs($user);

        putJson(
            route('questions.update', $question),
            [
                'question' => 'Lorem ipsum jeremias',
            ]
        )->assertJsonValidationErrors([
            'question' => 'The question must end with a question mark (?).',
        ]);
    });

    test('question min caracteres should be 10', function () {
        $user = User::factory()->create();

        $question = Question::factory()->create([
            'user_id'  => $user->id,
            'question' => 'Lorem?',
        ]);

        Sanctum::actingAs($user);

        putJson(
            route('questions.update', $question),
            [
                'question' => 'Lorem?',
            ]
        )->assertJsonValidationErrors([
            'question' => 'The question field must be at least 10 characters.',
        ]);
    });

    test('question should be unique only if id is different', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Question::factory()->create([
            'question' => 'Lorem ipsum jeremias?',
            'user_id'  => $user->id,
            'status'   => 'draft',
        ]);

        $question = Question::factory()->create([
            'user_id' => $user->id,
        ]);

        // 'Lorem ipsum jeremias?'
        putJson(
            route('questions.update', $question),
            [
                'question' => 'Lorem ipsum jeremias?',
            ]
        )->assertOk();
    });
});

describe('authorization', function () {

    test(
        'only the person who create thq eustion can update the same question ',
        function () {
            $user_owner = User::factory()->create();
            $user       = User::factory()->create();

            $question = Question::factory()->create([
                'user_id' => $user_owner->id,
            ]);

            Sanctum::actingAs($user);

            putJson(
                route('questions.update', $question),
                [
                    'question' => 'Lorem ipsum jeremias?',
                ]
            )->assertForbidden();

            assertDatabaseHas('questions', [
                'id'       => $question->id,
                'question' => $question->question,
            ]);
        }
    );
});
