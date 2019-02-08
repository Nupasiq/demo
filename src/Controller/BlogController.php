<?php declare(strict_types=1);

namespace App\Controller;

use App\DTO\BlogDTO;
use App\Services\DataManager\BlogDataManager;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Class BlogController
 *
 * @Rest\RouteResource("Blogs")
 */
class BlogController extends AbstractController
{
    /**
     * @Get("/blogs/{id}")
     *
     * @param BlogDataManager $manager
     * @param BlogDTO         $dto
     *
     * @return Response
     */
    public function getAction(BlogDataManager $manager, BlogDTO $dto): Response
    {
        $response = $this->view($manager->execute($dto));
        $response
            ->getContext()
            ->setGroups(['blog_single', 'user_single', 'show_topic']);

        return $this->handleView($response);
    }

    /**
     * @Get("/blogs")
     *
     * @param BlogDataManager $manager
     * @param BlogDTO         $dto
     *
     * @return Response
     */
    public function cgetAction(BlogDataManager $manager, BlogDTO $dto): Response
    {
        $response = $this->view($manager->execute($dto));
        $response
            ->getContext()
            ->setGroups(['blog_list', 'user_list', 'show_topic']);

        return $this->handleView($response);
    }

    /**
     * @Post("/blogs", defaults={"validation_groups": {"create"}})
     *
     * @param BlogDataManager $manager
     * @param BlogDTO         $dto
     *
     * @return Response
     */
    public function postAction(BlogDataManager $manager, BlogDTO $dto): Response
    {
        $this->denyAccessUnlessGranted('', $dto);
        $response = $this->view($manager->execute($dto));
        $response
            ->getContext()
            ->setGroups(['blog_single', 'user_single', 'show_topic']);

        return $this->handleView($response);
    }

    /**
     * @Put("/blogs", defaults={"validation_groups": {"update"}})
     *
     * @param BlogDataManager $manager
     * @param BlogDTO         $dto
     *
     * @return Response
     */
    public function putAction(BlogDataManager $manager, BlogDTO $dto): Response
    {
        $this->denyAccessUnlessGranted('', $dto);
        $response = $this->view($manager->execute($dto));
        $response
            ->getContext()
            ->setGroups(['blog_single', 'user_single', 'show_topic']);

        return $this->handleView($response);
    }

    /**
     * @Delete("/blogs")
     *
     * @param BlogDataManager $manager
     * @param BlogDTO         $dto
     *
     * @return Response
     */
    public function deleteAction(BlogDataManager $manager, BlogDTO $dto): Response
    {
        $this->denyAccessUnlessGranted('', $dto);
        $response = $this->view($manager->execute($dto));

        return $this->handleView($response);
    }
}
