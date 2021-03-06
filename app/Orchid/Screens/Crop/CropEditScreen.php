<?php

namespace App\Orchid\Screens\Crop;

use App\Actions\Crop\CreateCrop;
use App\Actions\Crop\DeleteCrop;
use App\Models\Crop\Crop;
use App\Orchid\Layouts\Crop\CropEditBasicLayout;
use App\Orchid\Layouts\Crop\CropEditGrowthLayout;
use App\Orchid\Layouts\Crop\CropEditPriceLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class CropEditScreen extends Screen
{
    protected $exists = false;

    public function __construct()
    {
        $this->name = __('Create Crop');
        $this->description = __('Create a new crop type');
    }

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Crop $crop): array
    {
        $this->crop = $crop;
        $this->exists = $crop->exists;

        if (!$crop->exists) {
            $this->name = __('Edit Crop');
            $this->description = __('Edit crop type details');
        }

        return [
            'crop' => $crop,
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            Button::make(__('Remove'))
                ->icon('trash')
                ->confirm(__('Once the crop type is deleted, all of its resources and data will be permanently deleted.'))
                ->method('remove')
                ->canSee($this->exists),

            Button::make(__('Save'))
                ->icon('check')
                ->method('save'),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            Layout::block(CropEditBasicLayout::class)
                ->title(__('Basic Information'))
                ->description(__('Update your crop\'s basic information')),

            Layout::block(CropEditPriceLayout::class)
                ->title(__('Price Information'))
                ->description(__('Update your crop\'s price information')),

            Layout::block(CropEditGrowthLayout::class)
                ->title(__('Growth Information'))
                ->description(__('Update your crop growth rate information'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->method('save')
                ),
        ];
    }

    /**
     * Remove a crop.
     *
     * @param Crop $crop
     * @throws \Exception
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Crop $crop)
    {
        return DeleteCrop::runOrchidAction($crop, null);
    }

    /**
     * Save a crop.
     *
     * @param Crop    $crop
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Crop $crop, Request $request)
    {
        return CreateCrop::runOrchidAction($crop, $request);
    }
}
