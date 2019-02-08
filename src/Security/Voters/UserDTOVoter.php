<?php declare(strict_types=1);

namespace App\Security\Voters;

use App\DTO\DTOInterface;
use App\DTO\UserDTO;
use App\Exception\AppException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Entity\AccessRightAction;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserDTOVoter
 */
class UserDTOVoter extends BaseVoter
{
    /**
     * @param string $attribute
     * @param mixed  $subject
     *
     * @return bool
     */
    protected function supports($attribute, $subject): bool
    {
        return $subject instanceof UserDTO;
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
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
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
    private function actionDecider(DTOInterface $dto)
    {
        switch (true) {
            case Request::METHOD_POST === $dto->getRequestType():
                $result = AccessRightAction::ALL;
                break;
            case Request::METHOD_PUT === $dto->getRequestType():
                $result = AccessRightAction::ALL;
                if (!is_array($dto->roles)) {
                    $result = ($this->user->getId() === $dto->getId())? AccessRightAction::MINE: AccessRightAction::ALL;
                }
                break;
            case Request::METHOD_DELETE === $dto->getRequestType():
                $result = ($this->user->getId() === $dto->getId())? AccessRightAction::MINE: AccessRightAction::ALL;
                break;
            default:
                $result = AccessRightAction::ALL;
                break;

        }

        return $result;
    }
}