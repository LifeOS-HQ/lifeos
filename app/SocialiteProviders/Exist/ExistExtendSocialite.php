<?php

namespace App\SocialiteProviders\Exist;

use SocialiteProviders\Manager\SocialiteWasCalled;

class ExistExtendSocialite
{
    /**
     * Register the provider.
     *
     * @param \SocialiteProviders\Manager\SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('exist', ExistProvider::class);
    }
}
