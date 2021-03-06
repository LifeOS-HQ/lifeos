<?php

namespace App\Console\Commands\Services\Exist\Api;

use App\Apis\Exist\Http;
use App\Models\Services\Data\Attributes\Attribute;
use App\Models\Services\Data\Attributes\Groups\Group;
use App\Models\Services\Data\Type;
use App\Models\Services\Data\Value;
use App\Models\Services\Service;
use App\Models\Services\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AttributesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'services:exist:api:attributes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $service = Service::where('slug', 'exist')->firstOrFail();

        $user = User::first();
        $user = Http::refresh($user);

        Http::setAccessToken($user->token);
        $rows = Http::get('users/$self/attributes/')->json();

        // dump($rows);

        foreach ($rows as $row) {
            $group = Group::updateOrCreate([
                'slug' => $row['group']['name'],
            ], [
                'name' => $row['group']['label'],
                'priority' => $row['group']['priority'],
            ]);

            $type = Type::updateOrCreate([
                'id' => $row['value_type'],
            ], [
                'name' => $row['value_type_description'],
            ]);

            $attribute = Attribute::updateOrCreate([
                'slug' => $row['attribute'],
            ], [
                'name' => $row['label'],
                'priority' => $row['priority'],
                'type_id' => $type->id,
                'group_id' => $group->id,
            ]);

            foreach ($row['values'] as $value) {
                Value::updateOrCreate([
                    'user_id' => $user->id,
                    'attribute_id' => $attribute->id,
                    'service_id' => $service->id,
                    'at' => (new Carbon($value['date']))->startOfDay(),
                ], [
                    'raw' => $value['value'],
                ]);
            }
        }
    }
}
