<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Notes extends Model
{
    use HasFactory;

    protected $table = 'notes';
    protected $fillable = [
        'title',
        'content',
        'relevant_links'
    ];
    protected $primaryKey = 'note_id';

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function files(): HasMany {
        return $this->hasMany(Files::class,'notes_id','note_id');
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($note) {
            // Delete files associated with the note
            foreach ($note->files as $file) {
                Storage::disk('public')->delete($file->file_path);
                $file->delete();
            }
        });
    }
}
