<?php

namespace App\Models\FarmerReport;

use App\Actions\Crop\CalculateExpectedProfitByVolume;
use App\Actions\Crop\CalculateExpectedYieldAmount;
use App\Actions\Crop\CalculateExpectedYieldDate;
use App\Models\Crop\Crop;
use App\Models\Crop\SeedStage;
use App\Models\Farmer\Farmer;
use App\Models\Farmland\Farmland;
use App\Models\ManagementUser;
use CloudinaryLabs\CloudinaryLaravel\MediaAlly;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Orchid\Filters\Filterable;

class FarmerReport extends Model
{
    use Filterable, HasFactory, MediaAlly;

    protected $fillable = [
        'reported_by',
        'seed_stage_id',
        'farmland_id',
        'crop_id',
        'verified',
        'verified_by',
        'volume_kg',
    ];

    protected $allowedFilters = [
        'id',
        'farmer_profile_id',
    ];

    protected $allowedSorts = [
        'id',
        'updated_at',
        'created_at',
    ];

    protected $casts = [
        'volume_kg' => 'float',
        'estimated_profit' => 'float',
        'estimated_yield_amount' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function (self $farmerReport) {
            if (!$farmerReport->isPlanted()) {
                return;
            }

            $crop = $farmerReport->crop;
            $farmland = $farmerReport->farmland;

            $createdAt = $farmerReport->created_at;
            $datePlanted = is_null($createdAt) ? now() : $createdAt;

            $estimatedYield = CalculateExpectedYieldAmount::run($crop, $farmland);
            $farmerReport->estimated_yield_amount = $estimatedYield;

            $estimatedDates = CalculateExpectedYieldDate::run($crop, $datePlanted);
            $farmerReport->estimated_yield_date_upper_bound = $estimatedDates['upper'];
            $farmerReport->estimated_yield_date_lower_bound = $estimatedDates['lower'];

            $estimatedProfit = CalculateExpectedProfitByVolume::run($crop, $estimatedYield);
            $farmerReport->estimated_profit = $estimatedProfit;
        });
    }

    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'reported_by');
    }

    public function verifier()
    {
        return $this->belongsTo(ManagementUser::class, 'verified_by');
    }

    public function image()
    {
        return $this->hasOne(Attachment::class, 'id', 'image')->withDefault();
    }

    public function seedStage()
    {
        return $this->belongsTo(SeedStage::class);
    }

    public function isPlanted()
    {
        $plantedId = (int) Cache::rememberForever('seed_stages:planted_id', function () {
            return $this->getSeedStageFromSlug('seeds-planted');
        });

        return (int) $this->seed_stage_id === $plantedId;
    }

    public function isHarvested()
    {
        $cropHarvestedId = (int) Cache::rememberForever('seed_stages:crops_harvested_id', function () {
            return $this->getSeedStageFromSlug('crops-harvested');
        });

        return (int) $this->seed_stage_id === $cropHarvestedId;
    }

    private function getSeedStageFromSlug(string $slug)
    {
        return SeedStage::where('slug', $slug)
            ->first()
            ->id;
    }

    public function farmland()
    {
        return $this->belongsTo(Farmland::class);
    }

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }
}
