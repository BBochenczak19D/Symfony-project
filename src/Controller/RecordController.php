<?php
namespace App\Controller;

use App\Repository\RecordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/record")]
class RecordController extends AbstractController{
    #[Route(
        name: "record_index",
        methods: ["GET"],
    )]
    public function index(RecordRepository $repository): Response {
        $records = $repository->findAll();

        //dump($records);

        return $this->render('record/index.html.twig', ['records' => $records]);
    }

    #[Route(
        '/{id}',
        name: "record_view",
        requirements: ['id' => '[1-9]\d*'],
        methods: ["GET"],
    )]
    public function view(RecordRepository $repository,int $id): Response{
        $record = $repository->findOneById($id);

        return $this->render(
            'record/view.html.twig',
            ['record' => $record]
        );
    }
}
