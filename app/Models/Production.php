<?php

namespace App\Models\Orders;

use App\Models\Core\Address;
use App\Traits\Models\RelationshipsTrait;
use Database\Factories\Orders\ProductionFactory;
use Illuminate\Database\Eloquent\Casts\ArrayObject;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $uid
 * @property int $check_id
 * @property string $status
 * @property int $phase
 * @property int $step_from
 * @property int|null $step_to
 * @property int $production_term
 * @property string $delivery_address_id
 * @property bool $delivery_address_confirmed
 * @property string $delivery_status
 * @property ArrayObject $data
 * @property Carbon|string $created_at
 * @property Carbon|string $updated_at
 *
 * @property Check $check
 * @property Address $deliveryAddress
 *
 * @method static ProductionFactory factory()
 */
class Production extends Model
{
    use RelationshipsTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uid',
        'check_id',
        'status',
        'phase',
        'step_from',
        'step_to',
        'production_term',
        'delivery_address_id',
        'delivery_address_confirmed',
        'delivery_status',
        'data',
    ];

    protected $casts = [
        'data' => AsArrayObject::class,
    ];

    /**
     * @return HasOne
     */
    public function check(): HasOne
    {
        return $this->hasOne(Check::class, 'id', 'check_id');
    }

    /**
     * @return HasOne
     */
    public function deliveryAddress(): HasOne
    {
        return $this->hasOne(Address::class, 'id', 'delivery_address_id');
    }
}
