<?php

namespace Database\Factories\Contacts;

use App\Models\Contacts\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => factory(\App\User::class)->create(),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'birthdate_at' => $this->faker->dateTime,
            'email' => $this->faker->email,
            'phone_number' => $this->faker->e164PhoneNumber,
            'mobile_number' => $this->faker->e164PhoneNumber,
            'website' => 'https://www.' . $this->faker->domainName,
            'twitter_id' => null,
            'instagram_id' => null,
            'first_met_at' => $this->faker->dateTime,
            'first_met_where' => $this->faker->city,
            'first_met_additional_info' => $this->faker->sentence,
            'job' => $this->faker->jobTitle,
            'last_talked_to_at' => $this->faker->dateTime,
            'street' => $this->faker->streetName,
            'city' => $this->faker->city,
            'postal_code' => $this->faker->postcode,
            'country_id' => null,
            'first_parent_id' => null,
            'second_parent_id' => null,
            'last_viewed_at' => $this->faker->dateTime,
        ];
    }
}
