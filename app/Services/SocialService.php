<?php

namespace App\Services;

use App\Repository\SocialRepository;

class SocialService
{
    public function __construct(
        protected SocialRepository $socialRepository,
    ) {}

    public function getSocials() {
        return $this->socialRepository->getSocials();
    }
}