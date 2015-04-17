<?php
/*
    @Author Prince Bangoura
    @product CMS build with PHP >=5.3.3 SYMFONY 2.5 - Bootstrap 3.3.2 - ckeditor 4.4.7
    @Function Engineer
    @Young entrepreneur
    @Date==>2015
    @V 0.1.1
*/
namespace Cms\ArticleBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ContenuRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContenuRepository extends EntityRepository
{
	public function getArticlesPublies()
	{
		$publier = 1;

		$qb = $this->createQueryBuilder('c')
				   ->Where('c.publier = :publier')
				   ->setParameter('publier', $publier)

				   ;

		return $qb->getQuery()
            	  ->getResult();

	}
}
