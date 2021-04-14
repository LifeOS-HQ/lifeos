<?php

namespace App\Models\Contacts;

use App\Traits\HasComments;
use Carbon\Carbon;
use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Contact extends Model
{
    use HasComments,
        HasFactory,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'contacts';

    protected $appends = [
        'name',
    ];

    protected $casts = [
        //
    ];

    protected $dates = [
        'birthdate_at',
        'first_met_at',
        'last_talked_to_at',
        'viewed_at',
    ];

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'birthdate_at',
        'birthdate_at_formatted',
        'email',
        'phone_number',
        'mobile_number',
        'website',
        'twitter_id',
        'instagram_id',
        'first_met_at',
        'first_met_at_formatted',
        'first_met_where',
        'first_met_additional_info',
        'job',
        'last_talked_to_at',
        'street',
        'city',
        'postal_code',
        'country_id',
        'first_parent_id',
        'second_parent_id',
        'last_viewed_at',
    ];

    /**
     * The booting method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            return true;
        });

        static::created(function($model)
        {
            return true;
        });

        static::updating(function($model)
        {
            return true;
        });
    }

    public function isDeletable() : bool
    {
        return true;
    }

    protected static function labels() : array
    {
        return [
            'nominativ' => [
                'singular' => 'Kontakt',
                'plural' => 'Kontakte',
            ],
        ];
    }

    public function getBirthdateAtFormattedAttribute() : string
    {
        return $this->birthdate_at->format('d.m.Y');
    }

    public function setBirthdateAtFormattedAttribute($value)
    {
        $this->attributes['birthdate_at'] = is_null($value) ? $value : (Carbon::createFromFormat('d.m.Y', $value))->startOfDay();
        Arr::forget($this->attributes, 'birthdate_at_formatted');
    }

    public function getFirstMetAtFormattedAttribute() : string
    {
        return $this->first_met_at->format('d.m.Y');
    }

    public function setFirstMetAtFormattedAttribute($value)
    {
        $this->attributes['first_met_at'] = is_null($value) ? $value : (Carbon::createFromFormat('d.m.Y', $value))->startOfDay();
        Arr::forget($this->attributes, 'first_met_at_formatted');
    }

    public function getNameAttribute() : string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }
}
