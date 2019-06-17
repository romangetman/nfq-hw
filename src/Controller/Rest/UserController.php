<?php


namespace App\Controller\Rest;


use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentNotFoundException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Rest\RouteResource("User")
 */
class UserController extends FOSRestController
{

    /**
     * Retrieves a User
     * @Rest\Get("/users/{userId}")
     * @return View
     */
    public function getUserObject(string $userId): View
    {
        $dm = $this->get('doctrine_mongodb.odm.default_document_manager');
        $utils = $this->get('utils');

        /* @var $repository \Doctrine\ODM\MongoDB\DocumentRepository */
        $repository = $dm->getRepository(User::class);

        $user = $repository->find($utils->mongoIdFromString($userId));

        if (is_null($user)) {
            throw new DocumentNotFoundException('Not found');
        }

        return View::create($user, Response::HTTP_OK);
    }

    /**
     * Retrieves all User
     * @Rest\Get("/users")
     * @return View
     */
    public function getAllUsers(): View
    {
        $dm = $this->get('doctrine_mongodb.odm.default_document_manager');
        /* @var $repository \Doctrine\ODM\MongoDB\DocumentRepository */
        $repository = $dm->getRepository(User::class);

        return View::create($repository->findAll(), Response::HTTP_OK);
    }

    /**
     * Creates a User resource
     * @Rest\Post("/users")
     * @param Request $request
     * @return View
     */
    public function createUser(Request $request): View
    {
        $dm = $this->get('doctrine_mongodb.odm.default_document_manager');
        $utils = $this->get('utils');

        $user = new User();
        $user->setName($request->request->get('name', ''));

        if ($request->request->has('group')) {

            $group = $request->request->get('group', '');

            $user->setGroup($utils->mongoIdFromString($group));

        }

        $dm->persist($user);
        $dm->flush();

        return View::create($user, Response::HTTP_CREATED);
    }

    /**
     * Removes a User resource
     * @Rest\Delete("/users/{userId}")
     * @param string $userId
     * @return View
     */
    public function deleteArticle(string $userId): View
    {
        $dm = $this->get('doctrine_mongodb.odm.default_document_manager');
        $utils = $this->get('utils');

        /* @var $repository \Doctrine\ODM\MongoDB\DocumentRepository */
        $repository = $dm->getRepository(User::class);

        $user = $repository->find($utils->mongoIdFromString($userId));

        if (is_null($user)) {
            throw new DocumentNotFoundException('Not found');
        }

        $dm->remove($user);
        $dm->flush();

        return View::create([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Replaces User resource
     * @Rest\Put("/users/{articleId}")
     * @return View
     */
    public function putUser(string $userId, Request $request): View
    {
        $dm = $this->get('doctrine_mongodb.odm.default_document_manager');
        $utils = $this->get('utils');

        /* @var $repository \Doctrine\ODM\MongoDB\DocumentRepository */
        $repository = $dm->getRepository(User::class);

        /**
         * @var User $user
         */
        $user = $repository->find($utils->mongoIdFromString($userId));

        if (is_null($user)) {
            throw new DocumentNotFoundException('Not found');
        }

        $user->setName($request->get('title'));
        $user->setGroup($request->get('content'));

        $dm->persist($user);
        $dm->flush();

        return View::create($user, Response::HTTP_OK);
    }

}
