<?php

namespace App\Console\Commands\Services\Exist\Api;

use Carbon\Carbon;
use App\Apis\Exist\Http;
use Illuminate\Support\Arr;
use App\Models\Services\User;
use Illuminate\Console\Command;
use App\Models\Services\Service;
use App\Models\Services\Data\Type;
use App\Models\Services\Data\Value;
use App\Models\Services\Data\Attributes\Attribute;
use App\Models\Services\Data\Attributes\Groups\Group;

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

    protected $service;

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
        $this->service = Service::where('slug', 'exist')->firstOrFail();

        $service_users = \App\Models\Services\User::with('user')->where('service_id', $this->service->id)->get();
        foreach ($service_users as $service_user) {
            $this->info('Processing user: ' . $service_user->user->name);
            $this->handleServiceUser($service_user);
        }
    }

    protected function handleServiceUser(\App\Models\Services\User $service_user): void
    {
        // Refresh, wenn nicht vorhanden oder abgelaufen
        if (is_null($service_user->expires_at) || $service_user->expires_at < now()) {
            $service_user = Http::refresh($service_user);
        }

        // Daten holen, oder expires_at zurücksetzten
        try {
            Http::setAccessToken($service_user->token);
            $rows = [];
            $page = 1;
            do {
                $response = Http::get('attributes/with-values', [
                    'page' => $page,
                    'days' => 30,
                ]);
                $data = $response->json();
                $this->handleAttributes($service_user, Arr::get($data, 'results', []));
                $page++;
            }
            while (! is_null(Arr::get($data, 'next')));
        } catch (\Throwable $th) {
            $service_user->update([
                'expires_at' => null,
                'expires_in' => null,
            ]);
            $this->error('Error: ' . $th->getMessage());
            return;
        }

    }

    private function handleAttributes(\App\Models\Services\User $service_user, array $rows): void
    {
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
                'slug' => $row['name'],
            ], [
                'name' => $row['label'],
                'priority' => $row['priority'],
                'type_id' => $type->id,
                'group_id' => $group->id,
            ]);

            foreach ($row['values'] as $value) {
                Value::updateOrCreate([
                    'user_id' => $service_user->user->id,
                    'attribute_id' => $attribute->id,
                    'service_id' => $this->service->id,
                    'at' => (new Carbon($value['date']))->startOfDay(),
                ], [
                    'raw' => $value['value'],
                ]);
            }
        }
    }
}
