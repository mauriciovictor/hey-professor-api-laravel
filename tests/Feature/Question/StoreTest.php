

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

    it('after creating a new question, I neeed to make sure that it creates on _draft_ status', function () {
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
