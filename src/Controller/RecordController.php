<?php

/**
 * This file is part of the SI project.
 *
 * (c) Students
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Repository\RecordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for record-related actions.
 */
#[Route('/record')]
class RecordController extends AbstractController
{
    /**
     * Displays list of all records.
     *
     * @param RecordRepository $repository the record repository
     *
     * @return Response
     */
    #[Route(
        name: 'record_index',
        methods: ['GET'],
    )]
    public function index(RecordRepository $repository): Response
    {
        $records = $repository->findAll();

        return $this->render('record/index.html.twig', ['records' => $records]);
    }

    /**
     * Displays a single record by ID.
     *
     * @param RecordRepository $repository the record repository
     * @param int              $id         the record ID
     *
     * @return Response
     */
    #[Route(
        '/{id}',
        name: 'record_view',
        requirements: ['id' => '[1-9]\d*'],
        methods: ['GET'],
    )]
    public function view(RecordRepository $repository, int $id): Response
    {
        $record = $repository->findOneById($id);

        return $this->render(
            'record/view.html.twig',
            ['record' => $record]
        );
    }
}
