<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Show\ShowMapper;

final class UserAdmin extends AbstractAdmin
{
    protected $datagridValues = [
    ];

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('name');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('id')
            ->add('name');
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('id', null, ['disabled' => true])
            ->add('name')
            ->add('group', ModelAutocompleteType::class, [
                'required' => false,
                'property' => 'name'

            ],
                [
                    'edit' => 'inline',
                    'inline' => 'table',
                    'targetEntity' => 'App\Document\Group',
                    'admin_code' => 'admin.group'
                ]
            );
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('name')
            ->add('created', 'timestamp');
    }
}
