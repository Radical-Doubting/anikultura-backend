<?php

namespace Tests\Feature;

use App\Models\Admin\Admin;
use Database\Seeders\Admin\AdminProfileSeeder;
use Database\Seeders\Admin\AdminSeeder;
use Database\Seeders\User\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchid\Support\Testing\ScreenTesting;
use Tests\TestCase;

class PlatformScreenTest extends TestCase
{
    use RefreshDatabase, ScreenTesting;

    /**
     * @group platform
     */
    public function testShouldShowScreen()
    {
        $this->seed([
            RoleSeeder::class,
            AdminSeeder::class,
            AdminProfileSeeder::class,
        ]);

        $screen = $this->screen('platform.main')->actingAs(Admin::find(1));

        $screen->display()
            ->assertSee('Empowering')
            ->assertSee('Analytics Dashboard');
    }
}
