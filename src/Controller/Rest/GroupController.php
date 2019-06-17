<?php


namespace App\Controller\Rest;


use App\Document\Group;
use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentNotFoundException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Rest\RouteResource("Group")
 */
class GroupController extends FOSRestController
{

    /**
     * Retrieves a Group
     * @Rest\Get("/groups/{groupId}")
     * @return View
     */
    public function getGroup(string $groupId): View
    {
        $dm = $this->get('doctrine_mongodb.odm.default_document_manager');
        $utils = $this->get('utils');

        /* @var $repository \Doctrine\ODM\MongoDB\DocumentRepository */
        $repository = $dm->getRepository(Group::class);

        $group = $repository->find($utils->mongoIdFromString($groupId));

        if (is_null($group)) {
            throw new DocumentNotFoundException('Not found');
        }

        return View::create($group, Response::HTTP_OK);
    }

    /**
     * Retrieves all Groups
     * @Rest\Get("/groups")
     * @return View
     */
    public function getAllGroups(): View
    {
        $dm = $this->get('doctrine_mongodb.odm.default_document_manager');

        /* @var $repository \Doctrine\ODM\MongoDB\DocumentRepository */
        $repository = $dm->getRepository(Group::class);

        $groups = $repository->findAll();

        return View::create($groups, Response::HTTP_OK);
    }

    /**
     * Creates a Group resource
     * @Rest\Post("/groups")
     * @param Request $request
     * @return View
     */
    public function createUser(Request $request): View
    {
        $dm = $this->get('doctrine_mongodb.odm.default_document_manager');

        $group = new Group();
        $group->setName($request->request->get('name', ''));

        $dm->persist($group);
        $dm->flush();

        return View::create($group, Response::HTTP_CREATED);
    }

    /**
     * Removes a Group resource
     * @Rest\Delete("/groups/{groupId}")
     * @return View
     */
    public function deleteArticle(string $groupId): View
    {
        $dm = $this->get('doctrine_mongodb.odm.default_document_manager');
        $utils = $this->get('utils');

        /* @var $repository \Doctrine\ODM\MongoDB\DocumentRepository */
        $repository = $dm->getRepository(Group::class);

        /* @var $users_repository \Doctrine\ODM\MongoDB\DocumentRepository */
        $users_repository = $dm->getRepository(User::class);

        $group = $repository->find($utils->mongoIdFromString($groupId));

        $users_in_group = $users_repository->findAll();

        if (count($users_in_group) > 0) {
            throw new \Exception('This group cannot be deleted because it still has users in it');
        }

        if (is_null($group)) {
            throw new DocumentNotFoundException('Not found');
        }

        $dm->remove($group);
        $dm->flush();

        return View::create([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Replaces Group resource
     * @Rest\Put("/groups/{groupId}")
     * @return View
     */
    public function putGroup(string $groupId, Request $request): View
    {
        $dm = $this->get('doctrine_mongodb.odm.default_document_manager');
        $utils = $this->get('utils');

        /* @var $repository \Doctrine\ODM\MongoDB\DocumentRepository */
        $repository = $dm->getRepository(Group::class);

        /**
         * @var Group $group
         */
        $group = $repository->find($utils->mongoIdFromString($groupId));

        if (is_null($group)) {
            throw new DocumentNotFoundException('Not found');
        }

        $group->setName($request->get('title'));
        $group->setGroup($request->get('content'));

        $dm->persist($group);
        $dm->flush();

        return View::create($group, Response::HTTP_OK);
    }

}
