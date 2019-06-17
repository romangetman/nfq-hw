<?php

declare(strict_types=1);

namespace App\Controller;

use App\Document\User;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

final class GroupAdminController extends CRUDController
{
    protected function preDelete(Request $request, $object)
    {
        $documentManager = $this->get('doctrine_mongodb.odm.default_document_manager');


        /* @var $repository \Doctrine\ODM\MongoDB\DocumentRepository */
        $repository = $documentManager->getRepository(User::class);

        $users_with_this_group = $repository->findBy(['group' => $object->getId()]);

        if ($users_with_this_group) {
            $this->addFlash('error', 'This group cannot be deleted because it still has users in it');

            return new RedirectResponse('/admin/app/group/list');

        }

        return null;
    }
}
