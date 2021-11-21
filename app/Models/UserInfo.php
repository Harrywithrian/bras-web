<?php

namespace App\Models;

use App\Core\Traits\SpatieLogsActivity;
use App\Models\Master\License;
use App\Models\Master\Region;
use App\Models\Transaksi\TFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class UserInfo extends Model
{
    use SpatieLogsActivity;

    /**
     * Prepare proper error handling for url attribute
     *
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        // if file avatar exist in storage folder
        $model = TFile::find($this->id_t_file_foto);
        if ($model) {
            $avatar = public_path(Storage::url($model->path));
            if (is_file($avatar) && file_exists($avatar)) {
                // get avatar url from storage
                return env('APP_URL') . Storage::url($model->path);
            }

            // check if the avatar is an external url, eg. image from google
            if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
                return $this->avatar;
            }
        }

        // no avatar, return blank avatar
        return asset(theme()->getMediaUrlPath() . 'avatars/blank.png');
    }

    /**
     * Prepare proper error handling for url attribute
     *
     * @return string
     */
    public function getLicenseUrlAttribute()
    {
        // if file avatar exist in storage folder
        $model = TFile::find($this->id_t_file_lisensi);
        if ($model) {
            $avatar = public_path(Storage::url($model->path));
            if (is_file($avatar) && file_exists($avatar)) {
                // get avatar url from storage
                return env('APP_URL') . Storage::url($model->path);
            }

            // check if the avatar is an external url, eg. image from google
            if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
                return $this->avatar;
            }
        }

        // no avatar, return blank avatar
        return asset(theme()->getMediaUrlPath() . 'avatars/blank.png');
    }


    /**
     * User info relation to user model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Unserialize values by default
     *
     * @param $value
     *
     * @return mixed|null
     */
    public function getCommunicationAttribute($value)
    {
        // test to un-serialize value and return as array
        $data = @unserialize($value);
        if ($data !== false) {
            return $data;
        } else {
            return null;
        }
    }

    /**
     * User info relation to role model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role', 'id');
    }

    /**
     * User info relation to tfile model as license file
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fileLicense()
    {
        return $this->belongsTo(TFile::class, 'id_t_file_lisensi', 'id');
    }

    /**
     * User info relation to tfile model as file image
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function filePhoto()
    {
        return $this->belongsTo(TFile::class, 'id_t_file_foto', 'id');
    }

    /**
     * User info relation to region model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function region()
    {
        return $this->belongsTo(Region::class, 'id_m_region', 'id');
    }

    /**
     * User info relation to license model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function license()
    {
        return $this->belongsTo(License::class, 'id_m_lisensi', 'id');
    }
}
