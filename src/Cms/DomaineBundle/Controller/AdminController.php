<?php
/*
    @Author Prince Bangoura
    @product CMS build with PHP >=5.3.3 SYMFONY 2.5 - Bootstrap 3.3.2 - ckeditor 4.4.7
    @Function Engineer
    @Young entrepreneur
    @Date==>2015
    @V 0.1.1
*/
namespace Cms\DomaineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Cms\DomaineBundle\Entity\Photo;
use Cms\DomaineBundle\Form\PhotoType;

use Cms\DomaineBundle\Entity\Menu;
use Cms\DomaineBundle\Form\MenuType;

use Cms\DomaineBundle\Entity\SousMenu;
use Cms\DomaineBundle\Form\SousMenuType;

use Cms\ContenuBundle\Entity\Contenu;
use Cms\ContenuBundle\Form\ContenuType;

use Cms\DomaineBundle\Entity\Logos;
use Cms\DomaineBundle\Form\LogosType;

use Cms\DomaineBundle\Entity\Document;
use Cms\DomaineBundle\Form\DocumentType;

class AdminController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$request = $this->getRequest();

    	$pages = $em->getRepository('PageBundle:Page')->findAll();

        return $this->render('DomaineBundle:Admin:index.html.twig');
    }

    /*===================menu homepage ========================== */
    public function menu_homepageAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $menus = $em->getRepository('DomaineBundle:Menu')->findAll();
        $pages = $em->getRepository('PageBundle:Page')->findAll();

        return $this->render('DomaineBundle:Admin:menu_homepage.html.twig',array(
            'menus' => $menus,
            'pages' => $pages,
            ));
    }
    /*===============Fin menu homepage===================*/

    /*===================ajouter menu ========================== */
    public function ajouter_menuAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $menu = new Menu();

        if($request->getMethod() == 'POST')
        {
            $choix_pages = $_POST['choix'];
            if($choix_pages != null)
            {
                foreach ($choix_pages as $idChoix_page) {
                    $page = $em->getRepository('PageBundle:Page')->find($idChoix_page);
                    if($page != null)
                    {
                        $menu->addPage($page);
                        $menu->setNom('BS_menu');
                        $menu->setPosition(0);
                        
                        $em->persist($menu);
                    }
                }//end for
                $em->flush();

                return $this->redirect($this->generateUrl('domaine_admin_menu_homepage'));
            }
            
        }

        return $this->redirect($this->generateUrl('domaine_admin_menu_homepage'));
    }
    /*===============Fin ajouter menu===================*/

    /*===================modifier_nom menu ========================== */
    public function modifier_nom_menuAction($idmenu)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $menu = $em->getRepository('DomaineBundle:Menu')->find($idmenu);

        $form = $this->createForm(new MenuType, $menu);

        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $menu= $form->getData();

                $em->flush();

                return $this->redirect($this->generateUrl('domaine_admin_menu_homepage'));
            }
            
        }

        return $this->render('DomaineBundle:Admin:modifier_nom_menu.html.twig', array(
            'form' =>$form->createView(),
            ));
    }
    /*===============Fin modifier_nom menu===================*/

    /*===================supprimer_page menu ========================== */
    public function supprimer_page_menuAction($idmenu)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $menu = $em->getRepository('DomaineBundle:Menu')->find($idmenu);

        $idpage = $request->query->get('page');
        $page = $em->getRepository('PageBundle:Page')->find($idpage);

      
        if($menu != null and $page != null)
        {
            $menu->removePage($page);

            $em->flush();

            return $this->redirect($this->generateUrl('domaine_admin_menu_homepage'));
    
        }

        return $this->redirect($this->generateUrl('domaine_admin_menu_homepage'));
    }
    /*===============Fin supprimer_page menu===================*/


    /*===================== gestion_parametres ==========================================*/
    public function gestion_parametresAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $contenus = $em->getRepository('ContenuBundle:Contenu')->findAll();
        $logos = $em->getRepository('DomaineBundle:Logos')->findAll();

        $logo_site = $em->getRepository('DomaineBundle:Logos')->findOneBy(array(
            'nom' => 'logoSite'
            ));

        $partenaires = $em->getRepository('ContenuBundle:Contenu')->findAll();


        return $this->render('DomaineBundle:Admin:gestion_parametres.html.twig',array(
            'contenus' => $contenus,
            'logos' => $logos,
            'logo_site' => $logo_site
            ));
    }
    /*===================== Fin gestion_parametres ==========================================*/

    /*===================== parametre_ajouter_logos_site ==========================================*/
    public function parametre_ajouter_logos_siteAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $logo_site = new Logos();

        //$form = $this->createForm(new LogosType, $logos_site);

        $form = $this->get('form.factory')->createBuilder('form', $logo_site)
                                  
                        ->add('file', 'file')

                        ->getForm();
                     ;

        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $logo_site = $form->getData();

                $logo_site->setNom('logoSite');

                $em->persist($logo_site);

                $em->flush();

                return $this->redirect($this->generateUrl('domaine_admin_parametre_homepage'));
            }
        }
        return $this->render('DomaineBundle:Admin:parametre_ajouter_logos_site.html.twig', array(
            'form' => $form->createView(),
            ));
    }
    /*===================== Fin parametre_ajouter_logos_site ==========================================*/

    /*===================== parametre_modifier_logos_site ==========================================*/
    public function parametre_modifier_logos_siteAction($idlogo)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $logo_site = $em->getRepository('DomaineBundle:Logos')->find($idlogo);

        $form = $this->createForm(new LogosType, $logo_site);

        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $logo_site = $form->getData();

                $logo_site->setNom('logoSite');

                $em->flush();

                return $this->redirect($this->generateUrl('domaine_admin_parametre_homepage'));
            }
        }
        return $this->render('DomaineBundle:Admin:parametre_modifier_logos_site.html.twig', array(
            'form' => $form->createView(),
            'logo' => $logo_site,
            ));
    }
    /*===================== Fin parametre_modifier_logos_site ==========================================*/

    /*===================== parametre_supprimer_logos_site ==========================================*/
    public function parametre_supprimer_logos_siteAction($idlogo)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $logo_site = $em->getRepository('DomaineBundle:Logos')->find($idlogo);

        if($logo_site != null)
        {
            $em->remove($logo_site);
            $em->flush();

            return $this->redirect($this->generateUrl('domaine_admin_parametre_homepage'));

        }
        return $this->redirect($this->generateUrl('domaine_admin_parametre_homepage'));
    }
    /*===================== Fin parametre_supprimer_logos_site ==========================================*/

    /*===================== parametre_ajouter_contact_contenu ==========================================*/
    public function parametre_ajouter_contact_contenuAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $contenu = new Contenu();

        $form = $this->get('form.factory')->createBuilder('form', $contenu)
                                  
                        ->add('telephone', 'text', array(
                            'label' => 'Numéro de téléphone :',))
                        ->getForm();
                     ;

        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $contenu = $form->getData();

                $em->persist($contenu);

                $em->flush();

                return $this->redirect($this->generateUrl('domaine_admin_parametre_homepage'));
            }
        }
        return $this->render('DomaineBundle:Admin:parametre_ajouter_contact_contenu.html.twig', array(
            'form' => $form->createView(),
            ));
    }
    /*===================== Fin parametre_ajouter_contact_contenu ==========================================*/

    /*===================== parametre_modifier_contact_contenu ==========================================*/
    public function parametre_modifier_contact_contenuAction($idcontenu)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $contenu = $em->getRepository('ContenuBundle:Contenu')->find($idcontenu);

        $form = $this->get('form.factory')->createBuilder('form', $contenu)
                                  
                        ->add('telephone', 'text', array(
                            'label' => 'Numéro de téléphone :',))
                        ->getForm();
                     ;

        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $contenu = $form->getData();
                $em->flush();

                return $this->redirect($this->generateUrl('domaine_admin_parametre_homepage'));
            }
        }
        return $this->render('DomaineBundle:Admin:parametre_modifier_contact_contenu.html.twig', array(
            'form' => $form->createView(),
            'contenu' => $contenu,
            ));
    }
    /*===================== Fin parametre_modifier_contact_contenu ==========================================*/

     /*===================== parametre_supprimer_contact_contenu ==========================================*/
    public function parametre_supprimer_contact_contenuAction($idcontenu)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $contenu = $em->getRepository('ContenuBundle:Contenu')->find($idcontenu);

        if($contenu != null)
        {
            $em->remove($contenu);
            $em->flush();

            return $this->redirect($this->generateUrl('domaine_admin_parametre_homepage'));

        }
        return $this->redirect($this->generateUrl('domaine_admin_parametre_homepage'));
    }
    /*===================== Fin parametre_supprimer_contact_contenu ==========================================*/



    /*===================== parametre_ajouter_reseaux_sociaux_contenu ==========================================*/
    public function parametre_ajouter_reseaux_sociaux_contenuAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $reseau_social = new Logos();

       $form = $this->createForm(new LogosType, $reseau_social);

        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $reseau_social = $form->getData();

                $em->persist($reseau_social);

                $em->flush();

                return $this->redirect($this->generateUrl('domaine_admin_parametre_homepage'));
            }
        }
        return $this->render('DomaineBundle:Admin:parametre_ajouter_reseaux_sociaux_contenu.html.twig', array(
            'form' => $form->createView(),
            ));
    }
    /*===================== Fin parametre_ajouter_reseaux_sociaux_contenu ==========================================*/

    /*===================== parametre_modifier_reseaux_sociaux_contenu ==========================================*/
    public function parametre_modifier_reseaux_sociaux_contenuAction($idlogo)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $reseau_social = $em->getRepository('DomaineBundle:Logos')->find($idlogo);

       $form = $this->createForm(new LogosType, $reseau_social);

        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $reseau_social = $form->getData();

                $em->flush();

                return $this->redirect($this->generateUrl('domaine_admin_parametre_homepage'));
            }
        }
        return $this->render('DomaineBundle:Admin:parametre_modifier_reseaux_sociaux_contenu.html.twig', array(
            'form' => $form->createView(),
            'reseau_social'=> $reseau_social
            ));
    }
    /*===================== Fin parametre_modifier_reseaux_sociaux_contenu ==========================================*/

    /*===================== parametre_supprimer_reseaux_sociaux_contenu ==========================================*/
    public function parametre_supprimer_reseaux_sociaux_contenuAction($idlogo)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $reseau_social = $em->getRepository('DomaineBundle:Logos')->find($idlogo);

        if($reseau_social != null)
        {
            $em->remove($reseau_social);
            $em->flush();

            return $this->redirect($this->generateUrl('domaine_admin_parametre_homepage'));
           
        }
        return $this->redirect($this->generateUrl('domaine_admin_parametre_homepage'));
           
    }
    /*===================== Fin parametre_supprimer_reseaux_sociaux_contenu ==========================================*/




    /*===================== ajouter_partenaire ==========================================*/
    public function ajouter_partenairesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $partenaire = new Contenu();

        $form = $this->get('form.factory')->createBuilder('form', $partenaire)

                    ->add('nompartenaire', 'text', array(
                            'label' => 'Nom :'))
                    
                    ->add('contenu', 'textarea', array(
                            'label' => 'Contenu',
                            'attr'=> array('class' => 'ckeditor')))

                    ->add('photo', new PhotoType())

                        ->getForm();
                     ;

        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $partenaire = $form->getData();

                $em->persist($partenaire);

                $em->flush();

                return $this->redirect($this->generateUrl('domaine_admin_parametre_homepage'));
            }
        }
        return $this->render('DomaineBundle:Admin:ajouter_partenaires.html.twig', array(
            'form' => $form->createView(),
            ));
    }
    /*===================== Fin ajouter_partenaire ==========================================*/

    /*===================== modifier_text_partenaire==========================================*/
    public function modifier_text_partenaireAction($idpartenaire)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $partenaire = $em->getRepository('ContenuBundle:Contenu')->find($idpartenaire);

        $form = $this->get('form.factory')->createBuilder('form', $partenaire)

                    ->add('nompartenaire', 'text', array(
                            'label' => 'Nom :'))

                        ->getForm();
                     ;

        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $partenaire = $form->getData();

                $em->persist($partenaire);

                $em->flush();

                return $this->redirect($this->generateUrl('domaine_admin_parametre_homepage'));
            }
        }
        return $this->render('DomaineBundle:Admin:modifier_text_partenaire.html.twig', array(
            'form' => $form->createView(),
            ));
    }
    /*===================== Fin modifier_text_partenaire==========================================*/

    /*===================== modifier_image_partenaire==========================================*/
    public function modifier_image_partenaireAction($idpartenaire)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $partenaire = $em->getRepository('ContenuBundle:Contenu')->find($idpartenaire);

        $form = $this->get('form.factory')->createBuilder('form', $partenaire)

                    ->add('photo', new PhotoType())

                        ->getForm();
                     ;

        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if($form->isValid())
            {
                $partenaire = $form->getData();

                $em->persist($partenaire);

                $em->flush();

                return $this->redirect($this->generateUrl('domaine_admin_parametre_homepage'));
            }
        }
        return $this->render('DomaineBundle:Admin:modifier_image_partenaire.html.twig', array(
            'form' => $form->createView(),
            ));
    }
    /*===================== Fin modifier_image_partenaire==========================================*/

     /*===================== supprimer_partenaire==========================================*/
    public function supprimer_partenaireAction($idpartenaire)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        $partenaire = $em->getRepository('ContenuBundle:Contenu')->find($idpartenaire);

        if($partenaire != null)
        {
            
            $em->remove($partenaire);

            $em->flush();

            return $this->redirect($this->generateUrl('domaine_admin_parametre_homepage'));
        }
        
        return $this->redirect($this->generateUrl('domaine_admin_parametre_homepage'));
            
    }
    /*===================== Fin supprimer_partenaire==========================================*/

    /*===================== Ajouter un document ==============================================*/
    public function ajouter_documentAction($idpage)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->get('request');

        $document = new Document();
        $form = $this->createForm(new DocumentType, $document);

        $page = $em->getRepository('PageBundle:Page')->find($idpage);

        if($request->getMethod() == 'POST')
        {
            $form->bind($request);

            if($form->isValid())
            {
                $em->persist($document);
                
                $page->addDocument($document);
                $em->flush();

             return $this->redirect($this->generateUrl('Page_admin_voir_une_page',array(
                'idpage' => $page->getId())));
            }
        }

        return $this->render('DomaineBundle:Admin:ajouter_document.html.twig',array(
            'form' => $form->createView(),
            'page' => $page,
            ));
    }
    /*===================Fin Ajouter un document ==============================================*/

    /*===================== modifier un document ==============================================*/
    public function modifier_documentAction($iddocument)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->get('request');
        $request2 = $this->getRequest();
        
        $idpage = $request2->query->get('idpage');

        $document = $em->getRepository('DomaineBundle:Document')->find($iddocument);
        $form = $this->createForm(new DocumentType, $document);

        $page = $em->getRepository('PageBundle:Page')->find($idpage);

        if($request->getMethod() == 'POST')
        {
            $form->bind($request);

            if($form->isValid())
            {
                $em->persist($document);
                
                $page->addDocument($document);

             return $this->redirect($this->generateUrl('Page_admin_voir_une_page',array(
                'idpage' => $page->getId())));
            }
        }

        return $this->render('DomaineBundle:Admin:modifier_document.html.twig',array(
            'form' => $form->createView(),
            'page' => $page,
            ));
    }
    /*===================Fin modifier un document ==============================================*/


    /*===================== Ajouter un document sous menu ======================================*/
    public function ajouter_document_sousmenuAction($idsousmenu)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->get('request');

        $document = new Document();
        $form = $this->createForm(new DocumentType, $document);

        $sousmenu = $em->getRepository('DomaineBundle:SousMenu')->find($idsousmenu);

        if($request->getMethod() == 'POST')
        {
            $form->bind($request);

            if($form->isValid())
            {
                $em->persist($document);
                
                $sousmenu->addDocument($document);
                $em->flush();

             return $this->redirect($this->generateUrl('Page_admin_voir_sous_menu_page',array(
                'idsousmenu' => $sousmenu->getId())));
            }
        }

        return $this->render('DomaineBundle:Admin:ajouter_document.html.twig',array(
            'form' => $form->createView(),
            'sousmenu' => $sousmenu,
            ));
    }
    /*===================Fin Ajouter un document sous menu ======================================*/

    /*===================== modifier un document sous menu ======================================*/
    public function modifier_document_sousmenuAction($iddocument)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->get('request');
        $request2 = $this->getRequest();

        $idsousmenu = $request2->query->get('idsousmenu');

        $document = $em->getRepository('DomaineBundle:Document')->find($iddocument);
        $form = $this->createForm(new DocumentType, $document);

        $sousmenu = $em->getRepository('DomaineBundle:SousMenu')->find($idsousmenu);

        if($request->getMethod() == 'POST')
        {
            $form->bind($request);

            if($form->isValid())
            {
                $em->persist($document);
                
                $sousmenu->addDocument($document);
                $em->flush();

             return $this->redirect($this->generateUrl('Page_admin_voir_sous_menu_page',array(
                'idsousmenu' => $sousmenu->getId())));
            }
        }

        return $this->render('DomaineBundle:Admin:ajouter_document.html.twig',array(
            'form' => $form->createView(),
            'sousmenu' => $sousmenu,
            ));
    }
    /*===================Fin modifier un document sous menu ======================================*/


    /*===================== voir un document ==============================================*/
    public function voir_documentAction($iddocument)
    {
        $em = $this->getDoctrine()->getManager();

        $document = $em->getRepository('DomaineBundle:Document')->find($iddocument);

        $fichier = $document->getNom();
        $chemin = $document->getWebPath();

        $response = new Response();
        $response->setContent(file_get_contents($chemin));
        $response->headers->set('Content-Type', 'application/pdf'); // modification du content-type pour forcer le téléchargement (sinon le navigateur internet essaie d'afficher le document)
        //$response->headers->set('Content-Type', 'application/docx');
        $response->headers->set('Content-disposition', 'filename='. $chemin);
         

        return $response;
    }
    /*===================== fin voir un document ==============================================*/

    /*===================== supprimer un document ==============================================*/
    public function supprimer_documentAction($iddocument)
    {
        $em = $this->getDoctrine()->getManager();

        $document = $em->getRepository('DomaineBundle:Document')->find($iddocument);

        if($document != null)
        {
            $em->remove($document);
            $em->flush();
            return $this->redirect($this->generateUrl('Page_admin_homepage'));
        }

        return $this->redirect($this->generateUrl('Page_admin_homepage'));
    }
    /*===================== fin supprimer un document ==============================================*/

}   
