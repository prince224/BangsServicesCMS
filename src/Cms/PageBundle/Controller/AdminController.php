<?php
/*
    @Author Prince Bangoura
    @product CMS build with PHP >=5.3.3 SYMFONY 2.5 - Bootstrap 3.3.2 - ckeditor 4.4.7
    @Function Engineer
    @Young entrepreneur
    @Date==>2015
    @V 0.1.1
*/
namespace Cms\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Cms\PageBundle\Entity\Page;
use Cms\PageBundle\Form\PageType;

use Cms\PageBundle\Entity\Section;
use Cms\PageBundle\Form\SectionType;

use Cms\ArticleBundle\Entity\Contenu;
use Cms\ArticleBundle\Form\ContenuType;

use Cms\DomaineBundle\Entity\SousMenu;
use Cms\DomaineBundle\Form\SousMenuType;

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

        $sections = $em->getRepository('PageBundle:Section')->findBy(array(
            'page' => $page));

        $photo_carousel = $em->getRepository('DomaineBundle:Photo')->findBy(array(
            'page' =>$page,
            'numero' => 1
            ));

        $photo_couverture = $em->getRepository('DomaineBundle:Photo')->findBy(array(
            'page' =>$page,
            'numero' => 0
            ));

       return $this->render('PageBundle:Admin:voir_une_page.html.twig', array(
                'page' => $page,
                'sousmenus' => $sousmenus,
                'photo_carousel'=> $photo_carousel,
                'photo_couverture' => $photo_couverture,
                'sections' => $sections,
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
                $section = $form->getData();

                //numero 1 pour les sections du carousel
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
                $photo= $form->getData();

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




    /*===================== Ajouter une couverture à une page ==========================================*/
    public function ajouter_image_couverture_pageAction($idpage)
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
                $section = $form->getData();

                //numero 1 pour les images couvertures
                $photo->setNumero('0');

                $em->persist($photo);
                $page->addPhoto($photo);

                $em->flush();

                //on fait une redirection vers la page homepage
                return $this->redirect($this->generateUrl('Page_admin_voir_une_page', array(
                    'idpage' => $page->getId(),
                    )));
            }
        }
        return $this->render('PageBundle:Admin:ajouter_image_couverture_page.html.twig', array(
            'form' => $form->createView(),
            'page' => $page,
            ));
    }
    /*===================== Fin de l'ajout image couverture page ==========================================*/

    /*===================== Mofifier une image de couverture à une page ==========================================*/
    public function modifier_image_couverture_pageAction($idphoto)
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
                $photo= $form->getData();

                $em->flush();

                //on fait une redirection vers la page homepage
                return $this->redirect($this->generateUrl('Page_admin_voir_une_page', array(
                    'idpage' => $page->getId(),
                    )));
            }
        }
        return $this->render('PageBundle:Admin:modifier_image_couverture_page.html.twig', array(
            'form' => $form->createView(),
            'page' => $page,
            ));
    }
    /*===================== Fin modification image couverture ==========================================*/

    /*===================== supprimer une image couverture==========================================*/
    public function supprimer_image_couverture_pageAction($idphoto)
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
    /*===================== Fin de la suppression d'une image couverture ===============================================*/
 


    /*===================== ajouter_section_page==========================================*/
    public function ajouter_section_pageAction($idpage)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $page = $em->getRepository('PageBundle:Page')->find($idpage);

        $section = new Section();

        $form = $this->createForm(new SectionType, $section);

        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            if($form->isValid())
            {
                $section = $form->getData();

               // $section->addCategory($categories);

                $em->persist($section);
                $page->addSection($section);

                $em->flush();

                //on fait une redirection vers la page homepage
                return $this->redirect($this->generateUrl('Page_admin_voir_une_page', array(
                    'idpage' => $page->getId(),
                    )));
            }
        }
        return $this->render('PageBundle:Admin:ajouter_section_page.html.twig', array(
            'form' => $form->createView(),
            ));
    }
    /*===================== Fin ajouter_section_page ==========================================*/

    /*===================== modifier_section_page==========================================*/
    public function modifier_section_pageAction($idsection)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $section = $em->getRepository('PageBundle:Section')->find($idsection);

        $form = $this->createForm(new SectionType, $section);

        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            if($form->isValid())
            {
                $section = $form->getData();

                $em->flush();

                //on fait une redirection vers la page homepage
                return $this->redirect($this->generateUrl('Page_admin_voir_une_page', array(
                    'idpage' => $section->getPage()->getId(),
                    )));
            }
        }
        return $this->render('PageBundle:Admin:modifier_section_page.html.twig', array(
            'form' => $form->createView(),
            ));
    }
    /*===================== Fin modifier_section_page ==========================================*/

    /*===================== supprimer_section_page==========================================*/
    public function supprimer_section_pageAction($idsection)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $section = $em->getRepository('PageBundle:Section')->find($idsection);

        $page = $section->getPage();

        if($section != null)
        {
            $em->remove($section);           
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
    /*===================== Fin supprimer_section_page ==========================================*/

     /*===================== supprimer_categories_section_page==========================================*/
    public function supprimer_categories_section_pageAction($idsection)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $idcategorie = $request->query->get('categorie');

        $section = $em->getRepository('PageBundle:Section')->find($idsection);
        $categorie = $em->getRepository('ArticleBundle:Categorie')->find($idcategorie);

        $page = $section->getPage();

        if($section != null and $categorie != null)
        {
            $section->removeCategory($categorie);           
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
    /*===================== Fin supprimer_categories_section_page ==========================================*/

     /*=============================== voir_sous_menu_page ===============================================*/
    public function voir_sous_menu_pageAction($idsousmenu)
    {
        $em = $this->getDoctrine()->getManager();
       
        $sousmenu = $em->getRepository('DomaineBundle:SousMenu')->find($idsousmenu);
        $page = $sousmenu->getPage();

        $sections = $em->getRepository('PageBundle:Section')->findBy(array(
            'sousmenu' => $sousmenu));

        $photo_carousel = $em->getRepository('DomaineBundle:Photo')->findBy(array(
            'sousmenu' =>$sousmenu,
            ));

       return $this->render('PageBundle:Admin:voir_sous_menu_page.html.twig', array(
                'page' => $page,
                'sousmenu' => $sousmenu,
                'photo_carousel'=> $photo_carousel,
                'sections' => $sections,
                ))
            ;
        
    }
    /*=============================== Fin page voir_sous_menu_page ============================================*/


    /*===================ajouter_sous menu_page ========================== */
    public function ajouter_sous_menu_pageAction($idpage)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $page = $em->getRepository('PageBundle:Page')->find($idpage);
        $sousmenu = new SousMenu();

        $form = $this->createForm(new SousMenuType, $sousmenu);

        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            if($form->isValid())
            {
                $sousmenu = $form->getData();

                $em->persist($sousmenu);
                $page->addSousmenu($sousmenu);

                $em->flush();

                //on fait une redirection vers la page homepage
                return $this->redirect($this->generateUrl('Page_admin_voir_sous_menu_page', array(
                    'idsousmenu' => $sousmenu->getId(),
                    )));
            }
        }
        return $this->render('PageBundle:Admin:ajouter_sous_menu_page.html.twig', array(
            'form' => $form->createView(),
            'page' => $page,
            ));
    }
    /*===============Fin ajouter_sous menu_page===================*/


    /*===================modifier_sous menu_page ========================== */
    public function modifier_sous_menu_pageAction($idsousmenu)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $sousmenu = $em->getRepository('DomaineBundle:SousMenu')->find($idsousmenu);
        $page = $sousmenu->getPage();

        $form = $this->createForm(new SousMenuType, $sousmenu);

        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            if($form->isValid())
            {
                $sousmenu = $form->getData();
                $em->flush();

                //on fait une redirection vers la page homepage
                return $this->redirect($this->generateUrl('Page_admin_voir_sous_menu_page', array(
                    'idsousmenu' => $sousmenu->getId(),
                    )));
            }
        }
        return $this->render('PageBundle:Admin:modifier_sous_menu_page.html.twig', array(
            'form' => $form->createView(),
            'page' => $page,
            'sousmenu' => $sousmenu,
            ));
    }
    /*===============Fin modifier_sous menu_page===================*/

    /*===================supprimer_sous menu_page ========================== */
    public function supprimer_sous_menu_pageAction($idsousmenu)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $sousmenu = $em->getRepository('DomaineBundle:SousMenu')->find($idsousmenu);
        $page = $sousmenu->getPage();

        if($sousmenu != null )
        {
            $em->remove($sousmenu);
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
    /*===============Fin supprimer_sous menu_page===================*/

    /*===================== Ajouter un carousel sous_menu page ==========================================*/
    public function ajouter_image_carousel_sous_menu_pageAction($idsousmenu)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $sousmenu = $em->getRepository('DomaineBundle:SousMenu')->find($idsousmenu);
        $photo = new Photo();

        $form = $this->createForm(new PhotoType, $photo);

        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            if($form->isValid())
            {
                $photo = $form->getData();

                $photo->setNumero(1);

                $em->persist($photo);
                $sousmenu->addPhoto($photo);

                $em->flush();

                //on fait une redirection vers la page homepage
                return $this->redirect($this->generateUrl('Page_admin_voir_sous_menu_page', array(
                    'idsousmenu' => $sousmenu->getId(),
                    )));
            }
        }
        return $this->render('PageBundle:Admin:ajouter_image_carousel_sous_menu_page.html.twig', array(
            'form' => $form->createView(),
            'sousmenu' => $sousmenu,
            ));
    }
    /*===================== Fin de l'ajout image carousel sous_menu page ==========================================*/

    /*===================== modifier un carousel sous_menu page ==========================================*/
    public function modifier_image_carousel_sous_menu_pageAction($idphoto)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $photo = $em->getRepository('DomaineBundle:Photo')->find($idphoto);
        $sousmenu = $photo->getSousmenu();

        $form = $this->createForm(new PhotoType, $photo);

        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            if($form->isValid())
            {
                $photo = $form->getData();
                $em->flush();

                //on fait une redirection vers la page homepage
                return $this->redirect($this->generateUrl('Page_admin_voir_sous_menu_page', array(
                    'idsousmenu' => $sousmenu->getId(),
                    )));
            }
        }
        return $this->render('PageBundle:Admin:modifier_image_carousel_sous_menu_page.html.twig', array(
            'form' => $form->createView(),
            'sousmenu' => $sousmenu,
            ));
    }
    /*===================== Fin modifier image carousel sous_menu page ==========================================*/

    /*===================== supprimer un carousel sous_menu page ==========================================*/
    public function supprimer_image_carousel_sous_menu_pageAction($idphoto)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $photo = $em->getRepository('DomaineBundle:Photo')->find($idphoto);
        $sousmenu = $photo->getSousmenu();

        if($photo != null)
        {
            $em->remove($photo);
            $em->flush();

            //on fait une redirection vers la page homepage
            return $this->redirect($this->generateUrl('Page_admin_voir_sous_menu_page', array(
                    'idsousmenu' => $sousmenu->getId(),
                    )));
            
        }
        //on fait une redirection vers la page homepage
        return $this->redirect($this->generateUrl('Page_admin_voir_sous_menu_page', array(
            'idsousmenu' => $sousmenu->getId(),
                    )));
        
    }
    /*===================== Fin supprimer image carousel sous_menu page ==========================================*/
   
    /*===================== ajouter_section_sous_menu_page==========================================*/
    public function ajouter_section_sous_menu_pageAction($idsousmenu)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $sousmenu = $em->getRepository('DomaineBundle:SousMenu')->find($idsousmenu);

        $section = new Section();

        $form = $this->createForm(new SectionType, $section);

        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            if($form->isValid())
            {
                $section = $form->getData();

                $em->persist($section);
                $sousmenu->addSection($section);

                $em->flush();

                //on fait une redirection vers la page homepage
                return $this->redirect($this->generateUrl('Page_admin_voir_sous_menu_page', array(
                    'idsousmenu' => $sousmenu->getId(),
                    )));

            }
        }
        return $this->render('PageBundle:Admin:ajouter_section_sous_menu_page.html.twig', array(
            'form' => $form->createView(),
            'sousmenu' => $sousmenu,
            ));
    }
    /*===================== Fin ajouter_section__sous_menupage ==========================================*/

    /*===================== modifier_section_sous_menu_page==========================================*/
    public function modifier_section_sous_menu_pageAction($idsection)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $section = $em->getRepository('PageBundle:Section')->find($idsection);

        $sousmenu = $section->getSousmenu();

        $form = $this->createForm(new SectionType, $section);

        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            if($form->isValid())
            {
                $section = $form->getData();

                $em->flush();

                //on fait une redirection vers la page homepage
                return $this->redirect($this->generateUrl('Page_admin_voir_sous_menu_page', array(
                    'idsousmenu' => $sousmenu->getId(),
                    )));

            }
        }
        return $this->render('PageBundle:Admin:modifier_section_sous_menu_page.html.twig', array(
            'form' => $form->createView(),
            'sousmenu' => $sousmenu,
            ));
    }
    /*===================== Fin modifier_section__sous_menupage ==========================================*/

    /*===================== supprimer_section_sous_menu_page==========================================*/
    public function supprimer_section_sous_menu_pageAction($idsection)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $section = $em->getRepository('PageBundle:Section')->find($idsection);

        $sousmenu = $section->getSousmenu();

        if($section != null)
        {
            $em->remove($section);
            $em->flush();

            //on fait une redirection vers la page homepage
            return $this->redirect($this->generateUrl('Page_admin_voir_sous_menu_page', array(
                    'idsousmenu' => $sousmenu->getId(),
                    )));
        }

        //on fait une redirection vers la page homepage
        return $this->redirect($this->generateUrl('Page_admin_voir_sous_menu_page', array(
                    'idsousmenu' => $sousmenu->getId(),
                    )));
    }
    /*===================== Fin supprimer_section__sous_menupage ==========================================*/

     /*===================== supprimer_section_sous_menu_page==========================================*/
    public function supprimer_categorie_section_sous_menu_pageAction($idsection)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $idcategorie = $request->query->get('categorie');

        $categorie = $em->getRepository('ArticleBundle:Categorie')->find($idcategorie);

        $section = $em->getRepository('PageBundle:Section')->find($idsection);

        $sousmenu = $section->getSousmenu();

        if($section != null and $categorie != null)
        {
            $section->removeCategory($categorie);
            $em->flush();

            //on fait une redirection vers la page homepage
            return $this->redirect($this->generateUrl('Page_admin_voir_sous_menu_page', array(
                    'idsousmenu' => $sousmenu->getId(),
                    )));
        }

        //on fait une redirection vers la page homepage
        return $this->redirect($this->generateUrl('Page_admin_voir_sous_menu_page', array(
                    'idsousmenu' => $sousmenu->getId(),
                    )));
    }
    /*===================== Fin supprimer_categorie_section__sous_menupage ==========================================*/

}
