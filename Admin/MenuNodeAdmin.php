<?php

namespace Msi\Bundle\MenuBundle\Admin;

use Msi\Bundle\AdminBundle\Admin\Admin;
use Doctrine\ORM\EntityRepository;
use Msi\Bundle\MenuBundle\Form\Type\MenuNodeTranslationType;

class MenuNodeAdmin extends Admin
{
    public function configure()
    {
        $this->options = array(
            'controller' => 'MsiMenuBundle:MenuNode:',
        );
    }

    public function buildIndexTable($builder)
    {
        $builder
            ->add('name', 'tree')
            ->add('', 'action', array('tree' => true))
        ;
    }

    public function buildForm($builder)
    {
        $qb = $this->getObjectManager()->getFindByQueryBuilder(
            array('a.menu' => $this->container->get('request')->query->get('parentId')),
            array('a.children' => 'c'),
            array('a.lvl' => 'ASC', 'a.lft' => 'ASC')
        );

        if ($this->getObject()->getId()) {
            $qb->andWhere('a.id != :match')->setParameter('match', $this->getObject()->getId());
            $i = 0;
            foreach ($this->getObject()->getChildren() as $child) {
                $qb->andWhere('a.id != :match'.$i)->setParameter('match'.$i, $child->getId());
                $i++;
            }
        }

        if ($this->getObject()->getChildren()->count()) {
            $qb->andWhere('a.lvl <= :bar')->setParameter('bar', $this->getObject()->getLvl() - 1);
        }

        $qb->andWhere('a.lvl <= :foo')->setParameter('foo', 2);

        $choices = $qb->getQuery()->execute();

        $builder
            ->add('translations', 'collection', array('label' => ' ', 'type' => new MenuNodeTranslationType(), 'options' => array(
                'label' => ' ',
            )))
            ->add('page', 'entity', array(
                'empty_value' => '',
                'class' => 'Msi\Bundle\PageBundle\Entity\Page',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->andWhere('a.route IS NULL')
                    ;
                },
            ))
            ->add('parent', 'entity', array(
                'class' => 'Msi\Bundle\MenuBundle\Entity\Menu',
                'choices' => $choices,
            ))
            ->add('targetBlank', 'checkbox')
        ;
    }
}
