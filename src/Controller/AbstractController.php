<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use FOS\RestBundle\Controller\ControllerTrait;
use FOS\RestBundle\View\ViewHandlerInterface;

/**
 * Class AbstractController
 */
abstract class AbstractController extends Controller
{
    use ControllerTrait;

    private $viewhandler;

    public function __construct(ViewHandlerInterface $viewHandler)
    {
        $this->viewhandler = $viewHandler;
    }

    public function prepareResponseData()
    {

    }

}