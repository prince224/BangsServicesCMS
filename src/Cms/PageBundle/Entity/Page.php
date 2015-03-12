<?php
/*
    @Author Prince Bangoura
    @product CMS build with PHP >=5.3.3 SYMFONY 2.5 - Bootstrap 3.3.2 - ckeditor 4.4.7
    @Function Engineer
    @Young entrepreneur
    @Date==>2015
    @V 0.1
*/
namespace Cms\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Page
 *
 * @ORM\Table(name="page")
 * @ORM\Entity(repositoryClass="Cms\PageBundle\Entity\PageRepository")
 */
class Page
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
    *
    * @ORM\OneToMany(targetEntity="Cms\DomaineBundle\Entity\Photo", mappedBy="page", cascade={"persist", "remove"})
    *
    */
    private $photos;

    /**
    *
    * @ORM\OneToMany(targetEntity="Cms\DomaineBundle\Entity\SousMenu", mappedBy="page", cascade={"persist", "remove"})
    *
    */
    private $sousmenus;

    /**
    * @ORM\OneToMany(targetEntity="Cms\PageBundle\Entity\Section", mappedBy="page", cascade={"persist", "remove"})
    *
    */
    private $sections;


    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Page
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->photos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add photos
     *
     * @param \Cms\DomaineBundle\Entity\Photo $photos
     * @return Page
     */
    public function addPhoto(\Cms\DomaineBundle\Entity\Photo $photos)
    {
        $this->photos[] = $photos;
        $photos->setPage($this);

        return $this;
    }

    /**
     * Remove photos
     *
     * @param \Cms\DomaineBundle\Entity\Photo $photos
     */
    public function removePhoto(\Cms\DomaineBundle\Entity\Photo $photos)
    {
        $this->photos->removeElement($photos);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Add sousmenus
     *
     * @param \Cms\DomaineBundle\Entity\SousMenu $sousmenus
     * @return Page
     */
    public function addSousmenu(\Cms\DomaineBundle\Entity\SousMenu $sousmenus)
    {
        $this->sousmenus[] = $sousmenus;
        $sousmenus->setPage($this);

        return $this;
    }

    /**
     * Remove sousmenus
     *
     * @param \Cms\DomaineBundle\Entity\SousMenu $sousmenus
     */
    public function removeSousmenu(\Cms\DomaineBundle\Entity\SousMenu $sousmenus)
    {
        $this->sousmenus->removeElement($sousmenus);
    }

    /**
     * Get sousmenus
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSousmenus()
    {
        return $this->sousmenus;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     * @return Page
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string 
     */
    public function getContenu()
    {
        return $this->contenu;
    }


    /**
     * Add sections
     *
     * @param \Cms\PageBundle\Entity\Section $sections
     * @return Page
     */
    public function addSection(\Cms\PageBundle\Entity\Section $sections)
    {
        $this->sections[] = $sections;
        $sections->setPage($this);

        return $this;
    }

    /**
     * Remove sections
     *
     * @param \Cms\PageBundle\Entity\Section $sections
     */
    public function removeSection(\Cms\PageBundle\Entity\Section $sections)
    {
        $this->sections->removeElement($sections);
    }

    /**
     * Get sections
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSections()
    {
        return $this->sections;
    }
}
