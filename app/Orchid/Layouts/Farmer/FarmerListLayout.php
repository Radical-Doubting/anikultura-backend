<?php

namespace App\Orchid\Layouts\Farmer;

use App\Models\Farmer\FarmerProfile;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class FarmerListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'farmer_profiles';

    /**
     * @return bool
     */
    protected function striped(): bool
    {
        return true;
    }

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('firstname', __('First Name'))
                ->render(function (FarmerProfile $farmer_profile) {
                    $user = $farmer_profile->user;
                    $has_user = !is_null($user);
                    $element = $has_user ? Link::make($user->first_name)
                        ->route('platform.farmers.edit', $farmer_profile->id) : __('None');

                    return $element;
                }),

            TD::make('middlename', __('Middle Name'))
                ->render(function (FarmerProfile $farmer_profile) {
                    $user = $farmer_profile->user;
                    $has_user = !is_null($user);
                    $element = $has_user ? Link::make($user->middle_name)
                        ->route('platform.farmers.edit', $farmer_profile->id) : __('None');

                    return $element;
                }),

            TD::make('lastname', __('Last Name'))
                ->cantHide()
                ->render(function (FarmerProfile $farmer_profile) {
                    $user = $farmer_profile->user;
                    $has_user = !is_null($user);
                    $element = $has_user ? Link::make($user->last_name)
                        ->route('platform.farmers.edit', $farmer_profile->id) : __('None');

                    return $element;
                }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->cantHide()
                ->width('100px')
                ->render(function (FarmerProfile $farmer_profile) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([
                            Link::make(__('Edit'))
                                ->route('platform.farmers.edit', $farmer_profile->id)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->icon('trash')
                                ->method('remove')
                                ->confirm(__('Once the farmer profile is deleted, all of its resources and data will be permanently deleted.'))
                                ->parameters([
                                    'id' => $farmer_profile->id,
                                ]),
                        ]);
                }),
        ];
    }
}
