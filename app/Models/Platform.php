<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Platform
 *
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string $slug
 * @property string $image_path
 * @property int $order
 * @property string|null $short_description
 * @property string|null $description
 * @property string $link
 * @property string|null $referral_code
 * @property int $invested_amount
 * @property float $percentage
 * @property string|null $rank
 * @property string|null $country
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category|null $category
 * @method static \Illuminate\Database\Eloquent\Builder|Platform newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Platform newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Platform query()
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereInvestedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform wherePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereReferralCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Platform whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Platform extends Model
{
    use HasFactory;

    /** @var array */
    protected $guarded = [];

    /**
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): HasOne
    {
        return $this->hasOne(Category::class);
    }
}
