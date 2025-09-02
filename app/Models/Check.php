<?php

namespace App\Models\Orders;

use App\Enums\Orders\CheckFileTypeEnum;
use App\Enums\Core\FileOwnerEnum;
use App\Enums\Orders\ProductionStatusEnum;
use App\Models\Core\File;
use App\Traits\Models\RelationshipsTrait;
use Database\Factories\Orders\CheckFactory;
use Illuminate\Database\Eloquent\Casts\ArrayObject;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $uid
 * @property int $stage_id
 * @property int $number
 * @property string $status
 * @property bool $available_to_doctor
 * @property Carbon|string $date
 * @property string $viewer_url
 * @property string $stl_files_link
 * @property int $steps_count
 * @property int $top_jaw_count
 * @property int $bottom_jaw_count
 * @property int $tariff_id
 * @property ArrayObject $data
 * @property Carbon|string $created_at
 * @property Carbon|string $updated_at
 *
 * @property File $fileM3D
 * @property File $fileSetupBottom
 * @property File $fileSetupTop
 * @property File $fileSource
 * @property Production $latestProduction
 * @property Collection|Production[] $productions
 * @property Collection|Production[] $productionsActive
 * @property Stage $stage
 * @property Tariff $tariff
 *
 * @method static CheckFactory factory()
 */
class Check extends Model
{
    use HasFactory,
        RelationshipsTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uid',
        'stage_id',
        'number',
        'status',
        'available_to_doctor',
        'date',
        'viewer_url',
        'stl_files_link',
        'steps_count',
        'top_jaw_count',
        'bottom_jaw_count',
        'tariff_id',
        'data->reject_reason',
    ];

    protected $casts = [
        'data' => AsArrayObject::class,
        'date' => 'date',
    ];

    public function fileM3D(): HasOne
    {
        return $this->hasOne(File::class, 'owner_id', 'id')
            ->where([
                'owner' => FileOwnerEnum::CHECK->value,
                'type' => CheckFileTypeEnum::M3D->value,
            ]);
    }

    public function fileSetupBottom(): HasOne
    {
        return $this->hasOne(File::class, 'owner_id', 'id')
            ->where([
                'owner' => FileOwnerEnum::CHECK->value,
                'type' => CheckFileTypeEnum::SETUP_BOTTOM->value,
            ]);
    }

    public function fileSetupTop(): HasOne
    {
        return $this->hasOne(File::class, 'owner_id', 'id')
            ->where([
                'owner' => FileOwnerEnum::CHECK->value,
                'type' => CheckFileTypeEnum::SETUP_TOP->value,
            ]);
    }

    public function fileSource(): HasOne
    {
        return $this->hasOne(File::class, 'owner_id', 'id')
            ->where([
                'owner' => FileOwnerEnum::CHECK->value,
                'type' => CheckFileTypeEnum::SOURCE->value,
            ]);
    }

    public function latestProduction(): HasOne
    {
        return $this->hasOne(Production::class, 'check_id', 'id')
            ->whereNot('status', ProductionStatusEnum::NEW->value)
            ->latest();
    }

    public function productions(): HasMany
    {
        return $this->hasMany(Production::class, 'check_id', 'id')
            ->orderBy('id');
    }

    public function productionsActive(): HasMany
    {
        return $this->hasMany(Production::class, 'check_id', 'id')
            ->whereIn('status', [
                ProductionStatusEnum::PRODUCTION->value, ProductionStatusEnum::DELIVERED->value
            ])
            ->orderBy('id');
    }

    public function stage(): HasOne
    {
        return $this->hasOne(Stage::class, 'id', 'stage_id');
    }

    public function tariff(): HasOne
    {
        return $this->hasOne(Tariff::class, 'id', 'tariff_id');
    }
}
