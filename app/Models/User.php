<?php

namespace App\Models;

use App\Core\Traits\SpatieLogsActivity;
use App\Models\Transaksi\TMatch;
use App\Models\Transaksi\TMatchEvaluation;
use App\Models\Transaksi\TMatchReferee;
use App\Models\Transaksi\TNotification;
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
     * User relation to play calling model
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function notification()
    {
        return $this->hasMany(TNotification::class, 'user', 'id');
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


    // get user data when logged in for API
    public static function getProfile($userId)
    {
        $user = User::with([
            'info' => function ($query) {
                return $query->select(['id', 'user_id', 'no_lisensi', 'id_m_lisensi', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'id_m_region', 'id_t_file_lisensi', 'id_t_file_foto', 'role']);
            },
            'info.license' => function ($query) {
                return $query->select(['id', 'license']);
            },
            'info.fileLicense' => function ($query) {
                return $query->select(['id']);
            },
            'info.filePhoto' => function ($query) {
                return $query->select(['id']);
            },
            'info.role' => function ($query) {
                return $query->select(['id', 'name']);
            },
            'info.region' => function ($query) {
                return $query->select(['id', 'kode', 'region']);
            }
        ])->where('id', $userId)->first(['id', 'username', 'name', 'email']);

        // serialize user rile license and photo
        if (isset($user->info->id_t_file_lisensi)) {
            $user->info->fileLicense['path'] = $user->info->getLicenseUrlAttribute();
        } else {
            $user->info->fileLicense = [
                'id' => null,
                'path' => null
            ];
        }
        
        if (isset($user->info->id_t_file_foto)) {
            $user->info->filePhoto['path'] = $user->info->getAvatarUrlAttribute();
        } else {
            $user->info->filePhoto = [
                'id' => null,
                'path' => $user->info->getAvatarUrlAttribute()
            ];
        }

        return $user;
    }
}
