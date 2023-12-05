<?php

namespace App\Validator\Constraints;

use App\Entity\SavingsBank;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SavingsBankConstraintValidator extends ConstraintValidator
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
        if (!$constraint instanceof SavingsBankConstraint) {
            throw new UnexpectedTypeException($constraint, SavingsBankConstraint::class);
        }

        if (!$value instanceof SavingsBank) {
            throw new UnexpectedTypeException($constraint, SavingsBank::class);
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