<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Song
 *
 * @package App\Models
 */
class Song extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable
        = [
            'name',
            'author',
            'link',
            'duration',
            'user_id'
        ];

    /**
     * @var array
     */
    protected $appends = ['user'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function parties()
    {
        return $this->belongsToMany(Party::class, 'song_party');
    }

    /**
     * @return object
     */
    public function getUserAttribute()
    {
        if ($this->artist_id) {
            return User::findOrFail($this->artist_id);
        }
    }
}
