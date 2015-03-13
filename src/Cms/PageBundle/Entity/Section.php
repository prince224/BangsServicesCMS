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
 * Section
 *
 * @ORM\Table(name="section")
 * @ORM\Entity(repositoryClass="Cms\PageBundle\Entity\SectionRepository")
 */
class Section
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;


    /**
     * @var integer
     *
     * @ORM\Column(name="numero", type="integer")
     */
    private $numero;

    /**
    * @ORM\ManyToMany(targetEntity="Cms\ArticleBundle\Entity\Contenu", cascade="persist")
    *
    */
    private $contenus;

    /**
    *
    * @ORM\ManyToOne(targetEntity="Cms\PageBundle\Entity\Page", inversedBy="sections")
    *
    */
    private $page;

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
     * Set numero
     *
     * @param integer $numero
     * @return Section
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero()
    {
        return $this->numero;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contenus = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add contenus
     *
     * @param \Cms\ArticleBundle\Entity\Contenu $contenus
     * @return Section
     */
    public function addContenus(\Cms\ArticleBundle\Entity\Contenu $contenus)
    {
        $this->contenus[] = $contenus;

        return $this;
    }

    /**
     * Remove contenus
     *
     * @param \Cms\ArticleBundle\Entity\Contenu $contenus
     */
    public function removeContenus(\Cms\ArticleBundle\Entity\Contenu $contenus)
    {
        $this->contenus->removeElement($contenus);
    }

    /**
     * Get contenus
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getContenus()
    {
        return $this->contenus;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Section
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
     * Set page
     *
     * @param \Cms\PageBundle\Entity\Page $page
     * @return Section
     */
    public function setPage(\Cms\PageBundle\Entity\Page $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \Cms\PageBundle\Entity\Page 
     */
    public function getPage()
    {
        return $this->page;
    }
}