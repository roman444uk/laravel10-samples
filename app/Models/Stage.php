<?php

namespace App\Models\Orders;

use App\Enums\Orders\CheckStatusEnum;
use App\Enums\Orders\StageFileTypeEnum;
use App\Enums\Orders\StageStatusEnum;
use App\Enums\Core\FileOwnerEnum;
use App\Models\Finance\Payment;
use App\Models\Loaders\StageLoaderTrait;
use App\Models\Core\Address;
use App\Models\Core\File;
use App\Models\Users\User;
use App\Support\Arr;
use App\Traits\Models\HasComments;
use App\Traits\Models\RelationshipsTrait;
use Database\Factories\Orders\StageFactory;
use Illuminate\Database\Eloquent\Casts\ArrayObject;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $uid
 * @property int $order_id
 * @property int $clinical_specialist_id
 * @property int $modeler_3d_id
 * @property int $technician_digitizer_id
 * @property int $technician_production_id
 * @property int $logistic_manager_id
 * @property string $type
 * @property StageStatusEnum|string $status
 * @property int $number
 * @property Carbon|string $guarantee_date
 * @property array $fields
 * @property string $fields_text
 * @property array $fields_laboratory
 * @property string $fields_type
 * @property int $take_casts_address_id
 * @property Carbon|string $take_casts_date
 * @property Carbon|string $take_casts_time
 * @property int $delivery_address_id
 * @property int $tariff_id
 * @property ArrayObject $doctors_data
 * @property ArrayObject $data
 * @property Carbon|string $created_at
 * @property Carbon|string $updated_at
 *
 * @property Check $checkAccepted
 * @property Collection|Check[] $checks
 * @property User $clinicalSpecialist
 * @property Address $deliveryAddress
 * @property Order $order
 * @property User $logisticManager
 * @property User $modeler3d
 * @property Payment[] $payments
 * @property File[] $photosAdditional
 * @property File $photoFrontal
 * @property File $photoFrontalWithEmma
 * @property File $photoFrontalWithOpenedMouth
 * @property File $photoFrontalWithSmile
 * @property File $photoLateralFromLeft
 * @property File $photoLateralFromRight
 * @property File $photoOcclusiveViewBottom
 * @property File $photoOcclusiveViewTop
 * @property File $photoOpg
 * @property File $photoToProfile
 * @property File $photoScanImpressionCommon
 * @property File $photoScanImpressionJawBottom
 * @property File $photoScanImpressionJawTop
 * @property File $photoScanImpressionOcclusionLeft
 * @property File $photoScanImpressionOcclusionRight
 * @property Address $takeCastsAddress
 * @property Tariff $tariff
 * @property User $technicianDigitizer
 * @property User $technicianProduction
 *
 * @method static StageFactory factory()
 */
class Stage extends Model
{
    use HasComments,
        HasFactory,
        LogsActivity,
        RelationshipsTrait,
        StageLoaderTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uid',
        'order_id',
        'type',
        'clinical_specialist_id',
        'modeler_3d_id',
        'technician_digitizer_id',
        'technician_production_id',
        'logistic_manager_id',
        'status',
        'number',
        'guarantee_date',
        'take_casts_address_id',
        'take_casts_date',
        'take_casts_time',
        'delivery_address_id',
        'fields',
        'fields_text',
        'fields_laboratory',
        'fields_type',
        'tariff_id',
        'doctors_data',
        'data',
        'data->scans_and_impressions_sub_tab',
    ];

    protected $casts = [
        'guarantee_date' => 'date',
        'take_casts_date' => 'date',
        'fields' => 'array',
        'fields_laboratory' => 'array',
        'doctors_data' => AsArrayObject::class,
        'data' => AsArrayObject::class,
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Fields.
     */
    public function fieldsGet(array|string $key, mixed $default = null): mixed
    {
        return Arr::getValue($this->fields, $key, $default);
    }

    public function fieldsEqual(array|string $key, mixed $value): bool
    {
        return $this->fieldsGet($key) === $value;
    }

    public function fieldsNotEqual(array|string $key, mixed $value): bool
    {
        return !$this->fieldsEqual($key, $value);
    }

    public function fieldsChecked(array|string $key): bool
    {
        return $this->fieldsGet($key) === true;
    }

    public function fieldsEmpty(array|string $key): bool
    {
        return $this->fieldsGet($key) === null;
    }

    public function fieldsUnchecked(array|string $key): bool
    {
        return $this->fieldsGet($key) === false;
    }

    public function fieldsEmptyOrUnchecked(array|string $key): bool
    {
        return $this->fieldsEmpty($key) || $this->fieldsUnchecked($key);
    }

    public function fieldsExists(array|string $key, mixed $value): bool
    {
        $fields = $this->fieldsGet($key, []);

        if (is_array($value)) {
            return is_array($fields) ? (bool)array_intersect($value, $fields) : in_array($fields, $value);
        } else {
            return in_array($value, is_array($fields) ? $fields : []);
        }
    }

    public function fieldsNotExists(array|string $key, mixed $value): bool
    {
        return !$this->fieldsExists($key, $value);
    }

    public function fieldsKeyExists(array|string $key, mixed $value): bool
    {
        $fields = $this->fieldsGet($key, []);

        if (is_array($value)) {
            return array_key_exists($fields) ? (bool)array_intersect($value, $fields) : in_array($fields, $value);
        } else {
            return array_key_exists($value, is_array($fields) ? $fields : []);
        }
    }

    /**
     * Fields laboratory.
     */
    public function fieldsLaboratoryGet(array|string $key, mixed $default = null): mixed
    {
        return Arr::getValue($this->fields_laboratory, $key, $default);
    }

    public function fieldsLaboratoryEqual(array|string $key, mixed $value): bool
    {
        return $this->fieldsLaboratoryGet($key) === $value;
    }

    public function fieldsLaboratoryNotEqual(array|string $key, mixed $value): bool
    {
        return !$this->fieldsLaboratoryEqual($key, $value);
    }

    public function fieldsLaboratoryChecked(array|string $key): bool
    {
        return $this->fieldsLaboratoryGet($key) === true;
    }

    public function fieldsLaboratoryEmpty(array|string $key): bool
    {
        return $this->fieldsLaboratoryGet($key) === null;
    }

    public function fieldsLaboratoryUnchecked(array|string $key): bool
    {
        return $this->fieldsLaboratoryGet($key) === false;
    }

    public function fieldsLaboratoryEmptyOrUnchecked(array|string $key): bool
    {
        return $this->fieldsLaboratoryEmpty($key) || $this->fieldsLaboratoryUnchecked($key);
    }

    public function fieldsLaboratoryExists(array|string $key, mixed $value): bool
    {
        $fields = $this->fieldsLaboratoryGet($key, []);

        if (is_array($value)) {
            return is_array($fields) ? (bool)array_intersect($value, $fields) : in_array($fields, $value);
        } else {
            return in_array($value, is_array($fields) ? $fields : []);
        }
    }

    public function fieldsLaboratoryNotExists(array|string $key, mixed $value): bool
    {
        return !$this->fieldsLaboratoryExists($key, $value);
    }

    public function fieldsLaboratoryKeyExists(array|string $key, mixed $value): bool
    {
        $fields = $this->fieldsLaboratoryGet($key, []);

        return array_key_exists($value, is_array($fields) ? $fields : []);
    }

    public function checkAccepted(): HasOne
    {
        return $this->hasOne(Check::class, 'stage_id', 'id')
            ->where('status', CheckStatusEnum::ACCEPTED->value);
    }

    public function checks(): HasMany
    {
        return $this->hasMany(Check::class, 'stage_id', 'id')
            ->orderBy('id');
    }

    public function clinicalSpecialist(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'clinical_specialist_id');
    }

    public function deliveryAddress(): HasOne
    {
        return $this->hasOne(Address::class, 'id', 'delivery_address_id');
    }

    public function logisticManager(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'logistic_manager_id');
    }

    public function modeler3d(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'modeler_3d_id');
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'stage_id', 'id');
    }

    public function photosAdditional(): HasMany
    {
        return $this->hasMany(File::class, 'owner_id', 'id')
            ->where(['owner' => FileOwnerEnum::STAGE, 'type' => StageFileTypeEnum::ADDITIONAL])
            ->orderBy('id');
    }

    public function photoFrontal(): HasOne
    {
        return $this->photo(StageFileTypeEnum::FRONTAL->value);
    }

    public function photoFrontalWithEmma(): HasOne
    {
        return $this->photo(StageFileTypeEnum::FRONTAL_WITH_EMMA->value);
    }

    public function photoFrontalWithSmile(): HasOne
    {
        return $this->photo(StageFileTypeEnum::FRONTAL_WITH_SMILE->value);
    }

    public function photoFrontalWithOpenedMouth(): HasOne
    {
        return $this->photo(StageFileTypeEnum::FRONTAL_WITH_OPENED_MOUTH->value);
    }

    public function photoLateralFromRight(): HasOne
    {
        return $this->photo(StageFileTypeEnum::LATERAL_FROM_RIGHT->value);
    }

    public function photoLateralFromLeft(): HasOne
    {
        return $this->photo(StageFileTypeEnum::LATERAL_FROM_LEFT->value);
    }

    public function photoOcclusiveViewBottom(): HasOne
    {
        return $this->photo(StageFileTypeEnum::OCCLUSIVE_VIEW_BOTTOM->value);
    }

    public function photoOcclusiveViewTop(): HasOne
    {
        return $this->photo(StageFileTypeEnum::OCCLUSIVE_VIEW_TOP->value);
    }

    public function photoOpg(): HasOne
    {
        return $this->photo(StageFileTypeEnum::OPG->value);
    }

    public function photoScanImpressionCommon(): HasOne
    {
        return $this->photo(StageFileTypeEnum::SCAN_IMPRESSION_COMMON->value);
    }

    public function photoScanImpressionJawBottom(): HasOne
    {
        return $this->photo(StageFileTypeEnum::SCAN_IMPRESSION_JAW_BOTTOM->value);
    }

    public function photoScanImpressionJawTop(): HasOne
    {
        return $this->photo(StageFileTypeEnum::SCAN_IMPRESSION_JAW_TOP->value);
    }

    public function photoScanImpressionOcclusionLeft(): HasOne
    {
        return $this->photo(StageFileTypeEnum::SCAN_IMPRESSION_OCCLUSION_LEFT->value);
    }

    public function photoScanImpressionOcclusionRight(): HasOne
    {
        return $this->photo(StageFileTypeEnum::SCAN_IMPRESSION_OCCLUSION_RIGHT->value);
    }

    public function photoToProfile(): HasOne
    {
        return $this->photo(StageFileTypeEnum::TO_PROFILE->value);
    }

    protected function photo(string $type): HasOne
    {
        return $this->hasOne(File::class, 'owner_id', 'id')
            ->where(['owner' => FileOwnerEnum::STAGE, 'type' => $type]);
    }

    public function takeCastsAddress(): HasOne
    {
        return $this->hasOne(Address::class, 'id', 'take_casts_address_id');
    }

    public function tariff(): HasOne
    {
        return $this->hasOne(Tariff::class, 'id', 'tariff_id');
    }

    public function technicianDigitizer(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'technician_digitizer_id');
    }

    public function technicianProduction(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'technician_production_id');
    }
}
