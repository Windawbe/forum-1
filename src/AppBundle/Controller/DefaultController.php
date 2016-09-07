<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->redirectToRoute("discutea_forum_homepage", array(), 301);
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }
    
     /**
     * 
     * Administration
     * 
     * @Route("/forum/admin/lstuser/", name="discutea_forum_admin_superdashboard")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * 
     */
    public function listUserAction()
    {
        $userManager = $this->get('fos_user.user_manager');
        $users = $userManager->findUsers();

        return $this->render('AppBundle::listUser.html.twig', array('users' =>   $users));
    }
    
    /** 
    * 
    * @Route("/forum/profile/edit/{id}", name="app_bundle_edituser")
    * 
    * 
    */
    public function editAction(Request $request, $id)
    {
        $userConnected = $this->getUser();
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));

        //$user = $em->getRepository('AppBundle:User')->find($id);

        if (!is_object($user) || $userConnected->getId() != $id && !$userConnected->hasRole('ROLE_SUPER_ADMIN')) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $event = new GetResponseUserEvent($user, $request);
        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $formFactory = $this->get('fos_user.profile.form.factory');

        $form = $formFactory->createForm();
        // si l'utilisateur n'a pas les droits super admin, on supprime le champs droit dans le formulaire
        if (!$userConnected->hasRole('ROLE_SUPER_ADMIN') || $userConnected->hasRole('ROLE_SUPER_ADMIN') &&  $userConnected->getId() == $id){
            $form->remove('roles');
        }
        $form->setData($user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            //$userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_user_show', array('id' => $id));
                $response = new RedirectResponse($url);
            }
            
            return $response;
        }
        
        //return $this->render('FOSUserBundle:Profile:edit/'.$id.'.html.twig', array('form' => $form->createView()));

        return $this->render('FOSUserBundle:Profile:edit.html.twig', array(
            'form' => $form->createView(),
            'id' => $id,
        ));
    }
    
    /**
     * 
     * @Route("/forum/profile/{id}", name="fos_user_user_show")
     * 
     */
    public function showAction($id)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));
        
        /*if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }*/

        return $this->render('FOSUserBundle:Profile:show.html.twig', array(
            'user' => $user
        ));
    }
    
    /**
     * 
     * @Route("/forum/profile/password/{id}", name="fos_user_user_password")
     * 
     */
    public function changePasswordAction(Request $request, $id)
    {
        //$user = $this->getUser();
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));
        
        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $event = new GetResponseUserEvent($user, $request);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.change_password.form.factory');

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');

            $event = new FormEvent($form, $request);

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_user_show', array('id' => $id));
                $response = new RedirectResponse($url);
            }

            return $response;
        }

        return $this->render('FOSUserBundle:ChangePassword:changePassword.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    /**
     * 
     * Administration
     * 
     * @Route("/forum/admin/role/", name="app_bundle_remove_user")
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * 
     */
    public function RemoveUserAction()
    {
        $userManager = $this->get('fos_user.user_manager');
        $users = $userManager->findUsers();

        return $this->render('AppBundle::listUser.html.twig', array('users' =>   $users));
    }
}
