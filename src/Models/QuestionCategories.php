<?php

declare(strict_types=1);

namespace MonthlyBasis\Question\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionCategories extends Model
{
    public $timestamps = false;

    protected $table = 'question_categories';

    protected $fillable = [
        'question_id',
        'category_id_1',
        'category_id_2',
        'category_id_3',
        'question_created_datetime_cached',
    ];
}
