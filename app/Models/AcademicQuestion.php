<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\MathTextService;

class AcademicQuestion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'question',
        'image_path',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => static::flushTestCache());
        static::deleted(fn () => static::flushTestCache());
        static::restored(fn () => static::flushTestCache());
    }

    protected static function cacheKey(): string
    {
        return 'exam.questions.academic.active.v1';
    }

    protected static function flushTestCache(): void
    {
        Cache::store('file')->forget(static::cacheKey());
    }

    public static function activeForTest(): Collection
    {
        return Cache::store('file')->rememberForever(static::cacheKey(), function () {
            return static::query()
                ->select(['id', 'question', 'image_path', 'order'])
                ->where('is_active', true)
                ->with([
                    'options' => function ($query) {
                        $query->select([
                            'id',
                            'academic_question_id',
                            'label',
                            'option_text',
                        ]);
                    },
                ])
                ->orderBy('order')
                ->get();
        });
    }

    public function options()
    {
        return $this->hasMany(AcademicQuestionOption::class);
    }

    public function answers()
    {
        return $this->hasMany(StudentAcademicAnswer::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) {
            return null;
        }

        if (Str::startsWith($this->image_path, ['http://', 'https://'])) {
            return $this->image_path;
        }

        return Storage::disk('public')->url($this->image_path);
    }

    public function getRenderedQuestionAttribute(): string
    {
        return app(MathTextService::class)->render($this->question);
    }
}
