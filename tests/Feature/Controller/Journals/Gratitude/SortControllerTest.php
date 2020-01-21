<?php

namespace Tests\Feature\Controller\Journals\Gratitude;

use App\Models\Journals\Gratitude\Gratitude;
use App\Models\Journals\Journal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class SortControllerTest extends TestCase
{
    protected $baseRouteName = 'journal.sort.gratitude';
    protected $baseViewPath = 'gratitude';
    protected $className = Gratitude::class;

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
            'update' => ['gratitude' => $id],
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

        $gratitudes_count = 3;
        $gratitudes = factory(Gratitude::class, $gratitudes_count)->create([
            'user_id' => $journal->user_id,
            'journal_id' => $journal->id
        ]);

        $data = [
            'ranks' => [],
        ];
        $order_columns = [];
        foreach ($gratitudes as $key => $gratitude) {
            $data['ranks'][($gratitudes_count - $key)] = $gratitude->id;
            $order_columns[$gratitude->id] = ($gratitudes_count - $key) + 1;
        }

        $this->signIn();

        $response = $this->put(route($this->baseRouteName . '.update', ['journal' => $journal->id]), $data)
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasNoErrors();

        foreach ($order_columns as $id => $order_column) {
            $this->assertDatabaseHas('gratitudes', [
                'id' => $id,
                'order_column' => $order_column,
            ]);
        }
    }
}
