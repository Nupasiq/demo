<?php declare(strict_types=1);

namespace App\Security\Voters;

use App\DTO\DTOInterface;
use App\DTO\BlogDTO;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Exception\AppException;
use App\Entity\AccessRightAction;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BlogDTOVoter
 */
class BlogDTOVoter extends BaseVoter
{
    /**
     * @param string $attribute
     * @param mixed  $subject
     *
     * @return bool
     */
    public function supports($attribute, $subject): bool
    {
        return $subject instanceof BlogDTO;
    }

    /**
     * @param string         $attribute
     * @param mixed          $subject
     * @param TokenInterface $token
     *
     * @return bool
     *
     * @throws AppException
     */
    public function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $this->init($subject, $token);
        $this->isActionAllowed($this->actionDecider($subject));
        if (!$this->isCan) {
            throw new AppException('Action not allowed', Response::HTTP_FORBIDDEN);
        }

        return true;
    }

    /**
     * @param DTOInterface $dto
     *
     * @return int
     */
    private function actionDecider(DTOInterface $dto): int
    {
        switch (true) {
            case Request::METHOD_POST === $dto->getRequestType():
                $result = AccessRightAction::MINE;
                break;
            case Request::METHOD_PUT === $dto->getRequestType():
                $blog = $this->getEntityById($dto->getId());
                $result = ($this->user->getId() === $blog->getOwner()->getId()) ? AccessRightAction::MINE: AccessRightAction::ALL;
                break;
            case Request::METHOD_DELETE === $dto->getRequestType():
                $blog = $this->getEntityById($dto->getId());
                $result = ($this->user->getId() === $blog->getOwner()->getId()) ? AccessRightAction::MINE: AccessRightAction::ALL;
                break;
            default:
                $result = AccessRightAction::MINE;
                break;
        }

        return $result;
    }
}
