<?php
/**
 * TodoItem controller.
 */

namespace App\Controller;

use App\Entity\TodoItem;
use App\Service\TodoItemService;
use App\Service\TodoItemServiceInterface;
use App\Form\Type\TodoItemType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;


/**
 * Class TodoItemController.
 */
#[Route('/todoitem')]
class TodoItemController extends AbstractController
{
    /**
     * TodoItem service.
     */
    private TodoItemServiceInterface $todoItemService;

    /**
     * Translator
     *
     * @var TranslatorInterface $translator;
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param TodoItemServiceInterface $todoItemService TodoItem service
     * @param TranslatorInterface $translator Translator
     */
    public function __construct(TodoItemServiceInterface $todoItemService, TranslatorInterface $translator)
    {
        $this->todoItemService = $todoItemService;
        $this->translator = $translator;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[Route(name: 'todoitem_index', methods: 'GET')]
    public function index(Request $request): Response
    {
        $pagination = $this->todoItemService->getPaginatedList(
            $request->query->getInt('page', 1)
        );

        return $this->render('todoItem/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param TodoItem $todoItem TodoItem
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'todoitem_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function show(TodoItem $todoItem): Response
    {
        return $this->render('todoitem/show.html.twig', ['todoItem' => $todoItem]);
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(
        '/create',
        name: 'todoitem_create',
        methods: 'GET|POST',
    )]
    public function create(Request $request): Response
    {
        $todoItem = new TodoItem();
        $form = $this->createForm(TodoItemType::class, $todoItem);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $this->todoItemService->save($todoItem);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('todoitem_index');
        }

        return $this->render(
            'todoitem/create.html.twig',
            ['form' => $form->createView()]
        );
    }


    /**
     * Edit action.
     *
     * @param Request  $request  HTTP request
     * @param TodoItem $todoItem TodoItem entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'todoitem_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function edit(Request $request, TodoItem $todoItem): Response
    {
        $form = $this->createForm(
            TodoItemType::class,
            $todoItem,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('todoitem_edit', ['id' => $todoItem->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->todoItemService->save($todoItem);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('todoitem_index');
        }

        return $this->render(
            'todoitem/edit.html.twig',
            [
                'form' => $form->createView(),
                'todoItem' => $todoItem,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request  $request  HTTP request
     * @param TodoItem $todoItem TodoItem entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'todoitem_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function delete(Request $request, TodoItem $todoItem): Response
    {

        $form = $this->createForm(
            TodoItemType::class,
            $todoItem,
            [
                'method' => 'DELETE',
                'action' => $this->generateUrl('todoItem_delete', ['id' => $todoItem->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->todoItemService->delete($todoItem);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('todoitem_index');
        }

        return $this->render(
            'todoitem/delete.html.twig',
            [
                'form' => $form->createView(),
                'todoItem' => $todoItem,
            ]
        );
    }

}
