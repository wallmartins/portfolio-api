<?php

namespace App\Repository;

use App\Model\Social;

class SocialRepository
{
    public function getSocials() {
        return Social::all();
    }
}