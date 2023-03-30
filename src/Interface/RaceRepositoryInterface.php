<?php

namespace App\Interface;

use App\Entity\Race;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface RaceRepositoryInterface
{
    public function import(UploadedFile $file, array $properties): Race;
}