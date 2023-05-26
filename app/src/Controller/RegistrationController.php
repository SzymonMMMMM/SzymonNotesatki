<?php

namespace App\Controller;

use App\Entity\User;

use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use App\Service\UserServiceInterface;
use App\Form\Type\UserType;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;



class RegistrationController extends AbstractController
{

    /**
     * User service.
     */
    private UserServiceInterface $userService;

    /**
     * Translator
     *
     * @var TranslatorInterface $translator;
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param UserServiceInterface $userService User service
     * @param TranslatorInterface $translator Translator
     */
    public function __construct(UserServiceInterface $userService, TranslatorInterface $translator)
    {
        $this->userService = $userService;
        $this->translator = $translator;
    }
    /** TODO ulepszyc automatyczny register wyciagnac autowiring i niech sam request bedzie*/
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** TODO czy da sie mniej dla password i dla role */
            $password = $form->get('password')->getData();
            $this->userService->passwordHasher($user, $password);

            $user->setRoles(['ROLE_USER']);

            $this->addFlash(
                'success',
                $this->translator->trans('message.registered_successfully')
            );
            $this->userService->save($user);

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
