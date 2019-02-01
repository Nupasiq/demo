<?php declare(strict_types=1);

namespace App\ArgumentResolver;

use App\DTO\DTOInterface;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;
use App\DTO\UserDTO;

/**
 * Class DTOValueResolver
 */
class DTOValueResolver implements ArgumentValueResolverInterface
{
    const USERS = 'users';
    const BLOG = 'blog';
    const GET_TYPE = 'id';

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var string
     */
    private $dto;

    /**
     * @var array
     */
    private $requestData;

    /**
     * AbstractDTOValueResolver constructor.
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param Request          $request
     * @param ArgumentMetadata $argument
     *
     * @return bool
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return UserDTO::class === $argument->getType();
    }

    /**
     * @param Request          $request
     * @param ArgumentMetadata $argument
     *
     * @return \Generator
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $this->dtoDataDecider($request);
        /**
         * @var DTOInterface $serialized
         */
        $serialized = $this->serializer->deserialize(json_encode($this->requestData), $this->dto, 'json');
        $serialized->setRequestType($request->getMethod());
        yield $serialized;
    }

    /**
     * @param Request $request
     */
    private function dtoDataDecider(Request $request)
    {

        switch (true) {
            case strpos($request->getUri(), self::USERS):
                $this->dto = UserDTO::class;
                $this->requestData = $request->get(self::USERS);
                break;
            case $request->get(self::BLOG):
                $this->dto = null;
                $this->requestData = $request->get(self::BLOG);
                break;
            default:
                break;
        }
        $this->onGet($request);
    }

    /**
     * @param Request $request
     */
    private function onGet(Request $request)
    {
        if ($request->getMethod() === Request::METHOD_GET) {
            $this->requestData[self::GET_TYPE] = (int) $request->get(self::GET_TYPE);
        }
    }
}
