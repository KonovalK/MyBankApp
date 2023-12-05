<?php

namespace App\Validator\Constraints;

use App\Entity\CardTemplate;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CardTemplateConstraintValidator extends ConstraintValidator
{

    /**
     * @param Security $security
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security               $security)
    {
    }

    /**
     * @param $value
     * @param Constraint $constraint
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof CardTemplateConstraint) {
            throw new UnexpectedTypeException($constraint, CardTemplateConstraint::class);
        }

        if (!$value instanceof CardTemplate) {
            throw new UnexpectedTypeException($constraint, CardTemplate::class);
        }

//        /** @var User $currentUser */
//        $currentUser = $this->security->getUser();
//
//        check if that company belongs to user
//        if ($value->getCompany()->getOwner() !== $currentUser) {
//            $this->context->addViolation("Select valid flight");
//        }
    }

}