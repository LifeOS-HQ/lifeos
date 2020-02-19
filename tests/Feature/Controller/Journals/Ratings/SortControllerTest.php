<?php

namespace Tests\Feature\Controller\Journals\Ratings;

use App\Models\Journals\Journal;
use App\Models\Journals\Rating;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class SortControllerTest extends TestCase
{
    protected $baseRouteName = 'journal.sort.rating';
    protected $baseViewPath = 'rating';
    protected $className = Rating::class;

    /**
     * @test
     */
    public function guests_can_not_access_the_following_routes()
    {
        $id = factory($this->className)->create()->id;

        $actions = [
            // 'index' => [],
            // 'store' => [],
            // 'show' => ['gratitude' => $id],
            // 'edit' => ['gratitude' => $id],
            'update' => ['rating' => $id],
            // 'destroy' => ['gratitude' => $id],
        ];
        $this->guestsCanNotAccess($actions);
    }

    /**
     * @test
     */
    public function a_user_can_sort_models()
    {
        $this->withoutExceptionHandling();

        $journal = factory(Journal::class)->create([
            'user_id' => $this->user->id,
        ]);

        $ratings_count = 3;
        $ratings = factory(Rating::class, $ratings_count)->create([
            'user_id' => $journal->user_id,
            'journal_id' => $journal->id
        ]);

        $data = [
            'ranks' => [],
        ];
        $order_columns = [];
        foreach ($ratings as $key => $gratitude) {
            $data['ranks'][($ratings_count - $key)] = $gratitude->id;
            $order_columns[$gratitude->id] = ($ratings_count - $key) + 1;
        }

        $this->signIn();

        $response = $this->put(route($this->baseRouteName . '.update', ['journal' => $journal->id]), $data)
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasNoErrors();

        foreach ($order_columns as $id => $order_column) {
            $this->assertDatabaseHas('journal_rating', [
                'id' => $id,
                'order_column' => $order_column,
            ]);
        }
    }
}
