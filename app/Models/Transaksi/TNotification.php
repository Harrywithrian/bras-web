<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TNotification extends Model
{
    use HasFactory;

    protected $table = "t_notification";

    const CREATED_AT = 'createdon';
    const UPDATED_AT = NULL;
    const DELETED_AT = NULL;

    protected $fillable = [
        'user',
        'type',
        'id_event_match',
        'status',
        'reply',
        'createdby',
        'createdon',
    ];

    // relation
    /**
     * Notification relation to event
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(TEvent::class, 'id_event_match', 'id');
    }

    /**
     * Notification relation to match
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function match()
    {
        return $this->belongsTo(TMatch::class, 'id_event_match', 'id');
    }

    /**
     * Notification relation to match
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scopeDescription($query)
    {

        $isMatch = $query->select('type')->first();
        // dd($isMatch->type);
        return $query
            ->when($isMatch->type === '1', function ($q) {
                // return event
                return $q->with('event');
            }, function ($q) {
                // return match
                return $q->with('match');
            });
        // ->when($this->type == 2, function ($q) {
        //     // return match
        //     return $q->with('match');
        // });
    }
}
