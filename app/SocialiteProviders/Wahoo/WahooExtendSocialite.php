<?php

namespace App\SocialiteProviders\Wahoo;

use SocialiteProviders\Manager\SocialiteWasCalled;

class WahooExtendSocialite
{
    /**
     * Register the provider.
     *
     * @param \SocialiteProviders\Manager\SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('wahoo', WahooProvider::class);
    }
}
