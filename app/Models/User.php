<?php

namespace App\Models;

use App\Core\Traits\SpatieLogsActivity;
use App\Models\Transaksi\TMatch;
use App\Models\Transaksi\TMatchEvaluation;
use App\Models\Transaksi\TMatchReferee;
use App\Models\Transaksi\TPlayCalling;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use HasFactory, Notifiable;
    use SpatieLogsActivity;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Prepare proper error handling for url attribute
     *
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->info) {
            return asset($this->info->avatar_url);
        }

        return asset(theme()->getMediaUrlPath() . 'avatars/blank.png');
    }

    /**
     * User relation to info model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function info()
    {
        return $this->hasOne(UserInfo::class);
    }

    /**
     * User relation to match referee model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function matchReferee()
    {
        return $this->hasMany(TMatchReferee::class, 'wasit', 'id');
    }

    /**
     * User relation to match evaluation model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function matchEvaluation()
    {
        return $this->hasMany(TMatchEvaluation::class, 'referee', 'id');
    }

    /**
     * User relation to play calling model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function playCalling()
    {
        return $this->hasMany(TPlayCalling::class, 'referee', 'id');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public static function getPengawas()
    {
        return User::with(['info', 'info.region'])->select('id', 'name')->role('Pengawas Pertandingan')->get()->map(function ($value) {
            return ['id' => $value->id, 'text' => $value->name . ' - ' . $value->info->region->region];
        })->toArray();
    }

    public static function getKoordinator()
    {
        return User::with(['info', 'info.region'])->select('id', 'name')->role('Koordinator Wasit')->get()->map(function ($value) {
            return ['id' => $value->id, 'text' => $value->name . ' - ' . $value->info->region->region];
        })->toArray();
    }

    public static function getWasit()
    {
        return User::with(['info', 'info.region'])->select('id', 'name')->role('Wasit')->get()->map(function ($value) {
            return ['id' => $value->id, 'text' => $value->name . ' - ' . $value->info->region->region];
        })->toArray();
    }
}
