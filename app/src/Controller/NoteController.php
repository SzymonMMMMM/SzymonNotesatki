<?php
/**
 * Notes controller.
 */

namespace App\Controller;

use App\Repository\NoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NoteController.
 */
#[Route('/note')]
class NoteController extends AbstractController
{
    /**
     * Index action.
     *
     * @param NoteRepository $repository Note repository
     *
     * @return Response HTTP response
     */
    #[Route(
        name: 'note_index',
        methods: 'GET'
    )]
    public function index(NoteRepository $repository): Response
    {
        $notes = $repository->findAll();

        return $this->render(
            'note/index.html.twig',
            ['notes' => $notes]
        );
    }

    /**
     * Show action.
     *
     * @param NoteRepository $repository Note repository
     * @param int              $id         Note id
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'note_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function show(NoteRepository $repository, int $id): Response
    {
        $note = $repository->findOneById($id);

        return $this->render(
            'note/show.html.twig',
            ['note' => $note]
        );
    }
}
