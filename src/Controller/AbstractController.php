<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use FOS\RestBundle\Controller\ControllerTrait;
use FOS\RestBundle\View\ViewHandlerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AbstractController
 */
abstract class AbstractController extends Controller
{
    use ControllerTrait;

    /**
     * @var ViewHandlerInterface
     */
    private $viewhandler;

    /**
     * AbstractController constructor.
     * @param ViewHandlerInterface $viewHandler
     */
    public function __construct(ViewHandlerInterface $viewHandler)
    {
        $this->viewhandler = $viewHandler;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function prepareResponseData(array $data)
    {
        $result = [
            'data' => $data,
            'code' => Response::HTTP_OK,
        ];

        return $result;
    }

}