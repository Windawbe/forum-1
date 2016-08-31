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
     * @Route("/admin/role/", name="discutea_forum_admin_superdashboard")
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
    * @Route("/edit/{id}", name="app_bundle_edituser")
    * @Security("is_granted('ROLE_SUPER_ADMIN')")
    * 
    */
    public function editAction(Request $request, $id)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));

        //$user = $em->getRepository('AppBundle:User')->find($id);

        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.profile.form.factory');

        $form = $formFactory->createForm();
        $form->setData($user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($user);

            $session = $this->getRequest()->getSession();
            $session->getFlashBag()->add('message', 'Successfully updated');
            $url = $this->generateUrl('matrix_edi_viewUser');
            $response = new RedirectResponse($url);
        }

        return $this->render('FOSUserBundle:Profile:edit.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    /**
     * 
     * @Route("/profile/{id}", name="fos_user_user_show")
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
}
