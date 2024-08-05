<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Files extends Model
{
    use HasFactory;

    protected $table = 'files';

    protected $fillable = [
      'file_path',
      'file_name',
        'file_raw_data'
    ];

    protected $appends = ['download_url','extension'];

    public function notes(): BelongsTo
    {
        return $this->belongsTo(Notes::class, 'notes_id', 'note_id');
    }


    public function getDownloadUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
    public function getExtensionAttribute()
    {
        return pathinfo($this->file_path, PATHINFO_EXTENSION);
    }
}
