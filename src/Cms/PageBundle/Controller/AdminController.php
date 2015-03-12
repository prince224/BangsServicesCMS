<?php

namespace Cms\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Cms\PageBundle\Entity\Page;
use Cms\PageBundle\Form\PageType;

use Cms\DomaineBundle\Entity\Photo;
use Cms\DomaineBundle\Form\PhotoType;


class AdminController extends Controller
{

	/*=============================== Page hompage du Cms ===============================================*/
    public function page_homepageAction()
    {
        $em = $this->getDoctrine()->getManager();
        $pages = $em->getRepository('PageBundle:Page')->findAll();

       return $this->render('PageBundle:Admin:page_homepage.html.twig', array(
                'pages' => $pages, 
                ))
            ;
        
    }
    /*=============================== Fin page homepage ============================================*/

    /*=============================== voir une page ===============================================*/
    public function voir_une_pageAction($idpage)
    {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('PageBundle:Page')->find($idpage);

        $sousmenus = $em->getRepository('DomaineBundle:SousMenu')->findBy(array(
            'page' => $page));

        $photo_carousel = $em->getRepository('DomaineBundle:Photo')->findBy(array(
            'page' =>$page,
            'numero' => 1
            ));

       return $this->render('PageBundle:Admin:voir_une_page.html.twig', array(
                'page' => $page,
                'sousmenus' => $sousmenus,
                'photo_carousel'=> $photo_carousel,
                ))
            ;
        
    }
    /*=============================== Fin page voir une page ============================================*/


    /*============== Ajouter une page=========================================*/
    public function ajouter_une_pageAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $page = new Page();

        $form = $this->createForm(new PageType, $page);

        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            if($form->isValid())
            {
                $page = $form->getData();

                $em->persist($page);
                $em->flush($page);

                //on fait une redirection vers la page homepage
                return $this->redirect($this->generateUrl('Page_admin_voir_une_page', array(
                    'idpage' => $page->getId(),
                    )));
            }
        }
    	return $this->render('PageBundle:Admin:ajouter_une_page.html.twig', array(
            'form' => $form->createView(),
            ));
    }
    /*============== Fin de l'ajout d'une page =========================================*/

    /*============== Modifier une page =========================================*/
    public function modifier_une_pageAction($idpage)
    {
    	$em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $page = $em->getRepository('PageBundle:Page')->find($idpage);

        $form = $this->createForm(new PageType, $page);

        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            if($form->isValid())
            {
                $page = $form->getData();

                $em->flush($page);

                //on fait une redirection vers la page homepage
                return $this->redirect($this->generateUrl('Page_admin_voir_une_page', array(
                    'idpage' => $page->getId(),
                    )));
            }
        }
        return $this->render('PageBundle:Admin:modifier_une_page.html.twig', array(
            'form' => $form->createView(),
            'page' => $page,
            ));
    }
    /*===================== Fin de la modification d'une page ===================================*/

    /*===================== Supprimer une page ==================================================*/
    public function supprimer_une_pageAction($idpage)
    {
    	$em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $page = $em->getRepository('PageBundle:Page')->find($idpage);

        if($page != null )
        {
            $em->remove($page);
            $em->flush();
            
            //on fait une redirection vers page homepage
            return $this->redirect($this->generateUrl('Page_admin_homepage'));

        }
       
        //on fait une redirection vers page homepage
            return $this->redirect($this->generateUrl('Page_admin_homepage'));
    }
    /*===================== Fin de la suppression d'une page =====================================*/


    /*===================== Ajouter un carousel à une page ==========================================*/
    public function ajouter_image_carousel_pageAction($idpage)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $page = $em->getRepository('PageBundle:Page')->find($idpage);
        $photo = new Photo();

        $form = $this->createForm(new PhotoType, $photo);

        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            if($form->isValid())
            {
                $photo = $form->getData();

                //numero 1 pour les photos du carousel
                $photo->setNumero('1');

                $em->persist($photo);
                $page->addPhoto($photo);

                $em->flush();

                //on fait une redirection vers la page homepage
                return $this->redirect($this->generateUrl('Page_admin_voir_une_page', array(
                    'idpage' => $page->getId(),
                    )));
            }
        }
        return $this->render('PageBundle:Admin:ajouter_image_carousel_page.html.twig', array(
            'form' => $form->createView(),
            'page' => $page,
            ));
    }
    /*===================== Fin de l'ajout image carousel page ==========================================*/


    /*===================== Mofifier une image à une page ==========================================*/
    public function modifier_image_carousel_pageAction($idphoto)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $photo = $em->getRepository('DomaineBundle:Photo')->find($idphoto);
        $page = $photo->getPage();
        
        $form = $this->createForm(new PhotoType, $photo);

        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            if($form->isValid())
            {
                $photo = $form->getData();

                $page->addPhoto($photo);

                $em->flush();

                //on fait une redirection vers la page homepage
                return $this->redirect($this->generateUrl('Page_admin_voir_une_page', array(
                    'idpage' => $page->getId(),
                    )));
            }
        }
        return $this->render('PageBundle:Admin:modifier_image_carousel_page.html.twig', array(
            'form' => $form->createView(),
            'page' => $page,
            ));
    }
    /*===================== Fin modification image carousel ==========================================*/

    /*===================== supprimer une image carousel==========================================*/
    public function supprimer_image_carousel_pageAction($idphoto)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $photo = $em->getRepository('DomaineBundle:Photo')->find($idphoto);
        $page = $photo->getPage();

        if($photo != null )
        {
            $em->remove($photo);
            $em->flush();
            
            //on fait une redirection vers la page homepage
            return $this->redirect($this->generateUrl('Page_admin_voir_une_page', array(
                    'idpage' => $page->getId(),
                    )));

        }
       
        //on fait une redirection vers la page homepage
            return $this->redirect($this->generateUrl('Page_admin_voir_une_page', array(
                    'idpage' => $page->getId(),
                    ))); 
    }
    /*===================== Fin de la suppression d'une image carousel ===============================================*/

    /*===================== Ajouter une photo pour le bandeau de la page accueil ==========================================*/

    public function ajouter_bandeau_pageAction($idpage)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $Pages = $em->getRepository('PageBundle:Page')->findAll();

        $Page = $em->getRepository('PageBundle:Page')->find($idpage);
        $photo = new Photo();

        $form = $this->createForm(new PhotoType, $photo);

        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            if($form->isValid())
            {
                $photo = $form->getData();

                //numéro 1 pour la première page
                $photo->setNumero('1');

                //l'onglet 1 pour la page homepage
                $photo->setOnglet('0');

                $em->persist($photo);
                $Page->addPhoto($photo);

                $em->flush();

                //on fait une redirection vers la page homepage
                return $this->redirect($this->generateUrl('domaine_admin_voir_page', array(
                    'idpage' => $Page->getId(),
                    )));
            }
        }
        return $this->render('PageBundle:Admin:ajouter_bandeau_page.html.twig', array(
            'form' => $form->createView(),
            'Page' => $Page,
            'Pages' => $Pages,
            ));
    }
    /*===================== Fin de l'ajout d'un bandeau page accueil ==========================================*/


    /*===================== modifier une photo pour le bandeau de la page accueil ==========================================*/

    public function modifier_bandeau_pageAction($idpage)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $Pages = $em->getRepository('PageBundle:Page')->findAll();

        $idPhoto = $request->query->get('idPhoto');

        $Page = $em->getRepository('PageBundle:Page')->find($idpage);

        $photo = $em->getRepository('PageBundle:Photo')->find($idPhoto);

        $form = $this->createForm(new PhotoType, $photo);

        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            if($form->isValid())
            {
                $photo = $form->getData();

                $Page->addPhoto($photo);

                $em->flush();

                //on fait une redirection vers la page homepage
                return $this->redirect($this->generateUrl('domaine_admin_voir_page', array(
                    'idpage' => $Page->getId(),
                    )));
            }
        }
        return $this->render('PageBundle:Admin:modifier_bandeau_page.html.twig', array(
            'form' => $form->createView(),
            'Page' => $Page,
            'Pages' => $Pages,
            ));
    }
    /*===================== Fin de la modification d'un bandeau page accueil ==========================================*/


    /*===================== supprimer bandeau page ==========================================*/

    public function supprimer_bandeau_pageAction($idpage, $idPhoto)
    {
        $em = $this->getDoctrine()->getManager();
        $Page = $em->getRepository('PageBundle:Page')->find($idpage);

        $photo_bandeau = $em->getRepository('PageBundle:Photo')->find($idPhoto);

        if($photo_bandeau != null )
        {
            $em->remove($photo_bandeau);
            $em->flush();
            
            //on fait une redirection vers la page homepage
            return $this->redirect($this->generateUrl('domaine_admin_voir_page', array(
                    'idpage' => $Page->getId(),
                    )));

        }
       
        return $this->render('PageBundle:Admin:index.html.twig');         
    }
    /*===================== Fin de la suppression bandeau page ==========================================*/

}
