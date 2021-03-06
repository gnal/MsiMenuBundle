<?php

namespace Msi\Bundle\MenuBundle\Entity;

use Msi\Bundle\AdminBundle\Entity\BaseManager;
use Doctrine\ORM\QueryBuilder;
use Msi\Bundle\AdminBundle\Admin\Admin;

class MenuManager extends BaseManager
{
    public function findRootById($id, $locale)
    {
        $qb = $this->getFindByQueryBuilder(
            array('ct.published' => true, 't.published' => true, 'a.id' => $id),
            array(
                'a.translations' => 't',

                'a.children' => 'c',
                'c.translations' => 'ct',

                'c.page' => 'p',
                'p.translations' => 'pt',

                'c.children' => 'cc',
                'cc.translations' => 'cct',

                'cc.children' => 'ccc',
                'ccc.translations' => 'ccct',
            ),
            array()
        );
        // $qb->addSelect('t');
        // $qb->addSelect('c');
        // $qb->addSelect('ct');
        // $qb->addSelect('p');
        // $qb->addSelect('pt');
        // $qb->addSelect('cc');
        // $qb->addSelect('cct');
        // $qb->addSelect('ccc');
        // $qb->addSelect('ccct');

        $orX = $qb->expr()->orX();

        $orX->add($qb->expr()->eq('pt.locale', ':ptlocale'));
        $qb->setParameter('ptlocale', $locale);

        $orX->add($qb->expr()->isNull('c.page'));

        $qb->andWhere($orX);

        $qb->andWhere($qb->expr()->eq('ct.locale', ':ctlocale'));
        $qb->setParameter('ctlocale', $locale);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findRootByName($name, $locale)
    {
        $qb = $this->getFindByQueryBuilder(
            array('ct.published' => true, 't.published' => true, 't.name' => $name),
            array(
                'a.translations' => 't',

                'a.children' => 'c',
                'c.translations' => 'ct',

                'c.page' => 'p',
                'p.translations' => 'pt',

                'c.children' => 'cc',
                'cc.translations' => 'cct',

                'cc.children' => 'ccc',
                'ccc.translations' => 'ccct',
            ),
            array()
        );
        // $qb->addSelect('t');
        // $qb->addSelect('c');
        // $qb->addSelect('ct');
        // $qb->addSelect('p');
        // $qb->addSelect('pt');
        // $qb->addSelect('cc');
        // $qb->addSelect('cct');
        // $qb->addSelect('ccc');
        // $qb->addSelect('ccct');

        $orX = $qb->expr()->orX();

        $orX->add($qb->expr()->eq('pt.locale', ':ptlocale'));
        $qb->setParameter('ptlocale', $locale);

        $orX->add($qb->expr()->isNull('c.page'));

        $qb->andWhere($orX);

        $qb->andWhere($qb->expr()->eq('ct.locale', ':ctlocale'));
        $qb->setParameter('ctlocale', $locale);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
