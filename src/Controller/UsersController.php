<?php declare(strict_types=1);

namespace App\Controller;

use App\DTO\UserDTO;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use App\Services\DataManager\UserDataManager;

/**
 * Class UsersController
 *
 * @Rest\RouteResource("Users")
 */
class UsersController extends AbstractController
{

    /**
     * @Get("/users/{id}")
     *
     * @param UserDataManager $manager
     * @param UserDTO         $dto
     *
     * @return Response
     */
    public function getAction(UserDataManager $manager, UserDTO $dto): Response
    {
        $response = $this->view($manager->execute($dto));
        $response
            ->getContext()
            ->setGroups(['user_single']);

        return $this->handleView($response);
    }

    /**
     * @Get("/users")
     *
     * @param UserDataManager $manager
     * @param UserDTO         $dto
     *
     * @return Response
     */
    public function cgetAction(UserDataManager $manager, UserDTO $dto): Response
    {
        $response = $this->view($manager->execute($dto));
        $response
            ->getContext()
            ->setGroups(['user_list']);

        return $this->handleView($response);
    }

    /**
     * @Post("/users", defaults={"validation_groups": {"create"}})
     *
     * @param UserDataManager $manager
     * @param UserDTO         $dto
     *
     * @return Response
     */
    public function postAction(UserDataManager $manager, UserDTO $dto): Response
    {
        $response = $this->view($manager->execute($dto));
        $response
            ->getContext()
            ->setGroups(['user_single']);

        return $this->handleView($response);
    }

    /**
     * @Put("/users", defaults={"validation_groups": {"update"}})
     *
     * @param UserDataManager $manager
     * @param UserDTO         $dto
     *
     * @return Response
     */
    public function putAction(UserDataManager $manager, UserDTO $dto): Response
    {
        $response = $this->view($manager->execute($dto));
        $response
            ->getContext()
            ->setGroups(['user_single']);

        return $this->handleView($response);
    }

    /**
     * @Delete("/users")
     *
     * @param UserDataManager $manager
     * @param UserDTO         $dto
     *
     * @return Response
     */
    public function deleteAction(UserDataManager $manager, UserDTO $dto): Response
    {
        $response = $this->view($manager->execute($dto));
        $response
            ->getContext()
            ->setGroups(['user_single']);

        return $this->handleView($response);
    }
}
