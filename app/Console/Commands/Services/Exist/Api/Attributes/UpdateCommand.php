<?php

namespace App\Console\Commands\Services\Exist\Api\Attributes;

use App\Apis\Exist\Http;
use App\Models\Days\Day;
use Illuminate\Console\Command;
use App\Models\Services\Service;
use App\Models\Services\Data\Value;
use Illuminate\Database\Eloquent\Collection;

class UpdateCommand extends Command
{
    protected $signature = 'services:exist:api:attributes:update
        {day : ID of the day}
        {--attribute=* : The attributes to update}';

    protected $description = 'Updates the attributes on the Exist API';

    protected $service;

    public function handle()
    {
        $day = Day::query()
            ->find($this->argument('day'));


            $this->service = Service::where('slug', 'exist')->firstOrFail();

            $service_user = \App\Models\Services\User::query()
            ->where('service_id', $this->service->id)
            ->where('user_id', $day->user_id)
            ->first();

        $payload = $this->getPayload($day, $this->option('attribute'));

        if (empty($payload)) {
            $this->info('No attributes to update');
            return self::SUCCESS;
        }

        if (is_null($service_user->expires_at) || $service_user->expires_at < now()) {
            $service_user = Http::refresh($service_user);
        }

        Http::setAccessToken($service_user->token);

        $response = Http::post('attributes/update/', $payload);
        $data = $response->json();

        dump($data);

        return self::SUCCESS;
    }

    private function getPayload(Day $day, array $attribute_ids): array
    {
        $values = $this->getValues($day, $attribute_ids);

        $payload = [];
        foreach ($values as $value) {
            $payload[] = [
                'name' => $value->attribute->slug,
                'date' => $day->date->format('Y-m-d'),
                'value' => $value->raw,
            ];
        }

        return $payload;
    }

    private function getValues(Day $day, array $attribute_ids): Collection
    {
        $query = Value::query()
            ->with('attribute')
            ->where('user_id', $day->user_id)
            ->where('at', $day->date);

        if (count($attribute_ids)) {
            $query->whereHas('attribute', function($query) use ($attribute_ids) {
                $query->whereIn('id', $attribute_ids);
            });
        }

        return $query->get();
    }
}
