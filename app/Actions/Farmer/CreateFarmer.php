<?php

namespace App\Actions\Farmer;

use App\Actions\User\CreateUser;
use App\Models\Farmer\Farmer;
use App\Models\User;
use App\Traits\AsOrchidAction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\Concerns\AsAction;
use Orchid\Support\Facades\Toast;

class CreateFarmer
{
    use AsAction;

    use AsOrchidAction;

    public function handle(Farmer $farmer, array $farmerData)
    {
        $createdAccountId = CreateUser::run($farmer, $farmerData['account']);
        $createdAccount = User::find($createdAccountId);

        $this->createProfile($createdAccount, $farmerData['profile']);

        CreateFarmerAddress::run($createdAccount->profile, $farmerData['address']);
    }

    private function createProfile(User $createdAccount, $profileData)
    {
        $farmerProfileId = CreateFarmerProfile::run($createdAccount->profile, $profileData);

        $createdAccount->update([
            'profile_id' => $farmerProfileId,
            'profile_type' => Farmer::$profilePath,
        ]);

        $createdAccount->refresh();
    }

    public function asOrchidAction($model, ?Request $request)
    {
        $this->validateIfFarmerAccountExistsAlready($model, $request);

        $this->handle($model, [
            'account' => $request->get('user'),
            'profile' => $request->get('farmer_profile'),
            'address' =>  $request->get('farmer_address'),
        ]);

        Toast::info(__('Farmer profile was saved successfully!'));

        return redirect()->route('platform.farmers');
    }

    private function validateIfFarmerAccountExistsAlready($farmer, Request $request)
    {
        $userNameShouldBeUnique = Rule::unique(Farmer::class, 'name')->ignore($farmer);
        $emailShouldBeUnique = Rule::unique(Farmer::class, 'email')->ignore($farmer);

        $request->validate([
            'user.name' => [
                'required',
                $userNameShouldBeUnique,
            ],
            'user.email' => [
                $emailShouldBeUnique,
            ],
        ]);
    }

    public function rules(): array
    {
        return [
            'farmer_profile.gender' => [
                'required',
            ],

            'farmer_profile.civil_status' => [
                'required',
            ],

            'farmer_profile.birthday' => [
                'required',
            ],

            'farmer_profile.age' => [
                'required',
            ],

            'farmer_profile.quantity_family_members' => [
                'required',
            ],

            'farmer_profile.quantity_dependents' => [
                'required',
            ],

            'farmer_profile.quantity_working_dependents' => [
                'required',
            ],

            'farmer_profile.highest_educational_status' => [
                'required',
            ],

            'farmer_profile.college_course' => [
                'required',
            ],

            'farmer_profile.current_job' => [
                'required',
            ],

            'farmer_profile.farming_years' => [
                'required',
            ],

            'farmer_profile.usual_crops_planted' => [
                'required',
            ],

            'farmer_profile.affiliated_organization' => [
                'required',
            ],

            'farmer_profile.tesda_training_joined' => [
                'required',
            ],

            'farmer_profile.nc_passer_status' => [
                'required',
            ],

            'farmer_profile.salary_periodicity' => [
                'required',
            ],

            'farmer_profile.estimated_salary' => [
                'required',
            ],

            'farmer_profile.social_status' => [
                'required',
            ],

            'farmer_profile.social_status_reason' => [
                'required',
            ],

            'farmer_address.house_number' => [
                'required',
            ],

            'farmer_address.street' => [
                'required',
            ],

            'farmer_address.barangay' => [
                'required',
            ],

            'farmer_address.municity' => [
                'required',
            ],

            'farmer_address.province' => [
                'required',
            ],

            'farmer_address.region_id' => [
                'required',
            ],
        ];
    }
}
