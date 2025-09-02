<?php

namespace App\Models\Orders;

use App\Enums\Chat\ChatContextEnum;
use App\Enums\Chat\ChatTypeEnum;
use App\Enums\Orders\OrderNomenclatureEnum;
use App\Models\Builders\OrderBuilder;
use App\Models\Chat\Chat;
use App\Models\Loaders\OrderLoaderTrait;
use App\Models\Core\Clinic;
use App\Models\Users\Doctor;
use App\Traits\Models\RelationshipsTrait;
use Database\Factories\Orders\OrderFactory;
use Illuminate\Database\Eloquent\Casts\ArrayObject;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $uid
 * @property int $doctor_id
 * @property int $patient_id
 * @property int $clinic_id
 * @property string $product_type
 * @property OrderNomenclatureEnum|string $nomenclature
 * @property string $printable_title
 * @property ArrayObject $doctors_data
 * @property Carbon|string $created_at
 * @property Carbon|string $updated_at
 *
 * @property Chat $chatInternal
 * @property Chat $chatWithDoctor
 * @property Clinic $clinic
 * @property Doctor $doctor
 * @property Patient $patient
 * @property Collection|OrderService[] $services
 * @property Collection|Stage[] $stages
 * @property Stage $stageActual
 * @property Stage $treatment
 *
 * @method static OrderFactory factory()
 */
class Order extends Model
{
    use HasFactory,
        OrderLoaderTrait,
        RelationshipsTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uid',
        'doctor_id',
        'patient_id',
        'clinic_id',
        'product_type',
        'nomenclature',
        'printable_title',
        'doctors_data',
    ];

    protected $casts = [
        'doctors_data' => AsArrayObject::class,
        'created_at' => 'date',
        'updated_at' => 'date',
    ];

    public function newEloquentBuilder($query): OrderBuilder
    {
        return new OrderBuilder($query);
    }

    public function chatInternal(): HasOne
    {
        return $this->hasOne(Chat::class, 'context_id', 'id')
            ->where([
                'context' => ChatContextEnum::ORDER->value,
                'type' => ChatTypeEnum::INTERNAL->value,
            ]);
    }

    public function chatWithDoctor(): HasOne
    {
        return $this->hasOne(Chat::class, 'context_id', 'id')
            ->where([
                'context' => ChatContextEnum::ORDER->value,
                'type' => ChatTypeEnum::WITH_DOCTOR->value,
            ]);
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class, 'clinic_id', 'id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(OrderService::class, 'order_id', 'id')
            ->orderBy('created_at', 'desc');
    }

    public function stages(): HasMany
    {
        return $this->hasMany(Stage::class)
            ->orderBy('id');
    }

    public function stageActual(): HasOne
    {
        return $this->hasOne(Stage::class)->latest('id');
    }

    public function treatment(): HasOne
    {
        return $this->hasOne(Stage::class)->oldest();
    }
}
