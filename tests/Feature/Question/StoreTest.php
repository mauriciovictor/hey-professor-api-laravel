

    <?php

    use App\Models\{Question, User};
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

        test('question min caracteres should be 10', function () {
            $user = User::factory()->create();
            Sanctum::actingAs($user);

            postJson(
                route('questions.store'),
                [
                    'question' => 'Lorem?',
                ]
            )->assertJsonValidationErrors([
                'question' => 'The question field must be at least 10 characters.',
            ]);
        });

        test('question should be unique', function () {
            $user = User::factory()->create();
            Sanctum::actingAs($user);

            Question::factory()->create([
                'question' => 'Lorem ipsum jeremias?',
                'user_id'  => $user->id,
                'status'   => 'draft',
            ]);

            // 'Lorem ipsum jeremias?'
            postJson(
                route('questions.store'),
                [
                    'question' => 'Lorem ipsum jeremias?',
                ]
            )->assertJsonValidationErrors([
                'question' => 'already been taken.',
            ]);
        });
    });

    test('after creating wwe should return a status 201 with the following', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $request = postJson(route('questions.store'), [
            'question' => 'Lorem ipsum jeremias?',
        ])->assertCreated();

        $question = Question::latest()->first();

        $request->assertJson([
            'data' => [
                'id'         => $question->id,
                'question'   => $question->question,
                'status'     => $question->status,
                'created_by' => [
                    'id'   => $question->user->id,
                    'name' => $question->user->name,
                ],
                'user_id'    => $question->user_id,
                'created_at' => $question->created_at->format('Y-m-d'),
                'updated_at' => $question->updated_at->format('Y-m-d'),
            ],
        ]);
    });
