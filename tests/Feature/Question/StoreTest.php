

    <?php

    use App\Models\User;
    use Laravel\Sanctum\Sanctum;

    use function Pest\Laravel\{assertDatabaseHas, postJson};

    it('should be able to create a new question', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        postJson(route('questions.store'), [
            'question' => 'Lorem ipsum jeremias?',
        ])->assertSuccessful();

        assertDatabaseHas('questions', [
            'user_id'  => $user->id,
            'status'   => 'draft',
            'question' => 'Lorem ipsum jeremias?',
        ]);
    });

    test('after creating a new question, I neeed to make sure that it creates on _draft_ status', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        postJson(route('questions.store'), [
            'question' => 'Lorem ipsum jeremias?',
        ])->assertSuccessful();

        assertDatabaseHas('questions', [
            'user_id'  => $user->id,
            'status'   => 'draft',
            'question' => 'Lorem ipsum jeremias?',
        ]);
    });

    describe('validation rules', function () {
        test('question required ', function () {
            $user = User::factory()->create();
            Sanctum::actingAs($user);

            postJson(
                route('questions.store'),
                []
            )->assertJsonValidationErrors([
                'question' => 'required',
            ]);
        });

        test('question ending with question mark', function () {
            $user = User::factory()->create();
            Sanctum::actingAs($user);

            postJson(
                route('questions.store'),
                [
                    'question' => 'Lorem ipsum jeremias',
                ]
            )->assertJsonValidationErrors([
                'question' => 'The question must end with a question mark (?).',
            ]);
        });
    });
