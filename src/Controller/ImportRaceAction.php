<?php

namespace App\Controller;

use App\Entity\Race;
use App\Interface\RaceRepositoryInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

#[AsController]
class ImportRaceAction extends AbstractController
{
    public function __construct(private readonly RaceRepositoryInterface $raceRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function __invoke(Request $request): Race
    {
        $file = $request->files->get('file');
        if (!$file) {
            throw new UnprocessableEntityHttpException("'file' is required");
        }

        $properties = [
            'title' => $request->get('title'),
            'raceDate' => new \DateTime($request->get('raceDate')),
        ];

        return $this->raceRepository->import($file, $properties);
    }
}