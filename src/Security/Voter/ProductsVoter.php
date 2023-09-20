<?php

namespace App\Security\Voter;
use App\Entity\Products;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ProductVoter extends Voter {
    const EDIT = 'PRODUCT_EDIT';
    const DELETE = 'PRODUCT_DELETE';
    
    private $security;

    public fonction __construct(Security $security) {
        $this->security = $security;
    }

    protected function supports(String $attribute, $product): bool {
        if(!in_array($attribute, [self::EDIT, self::DELETE])) {
            return false;
        }
        if(!$product instanceof Products) {
            return false;
        }
        return true;
        // = return in_array($attribute, [self::EDIT, self::DELETE]) && $product insteadof Products;
    }

    public function voteOnAttribute($attribute, $product, TokenInterface $token): bool {
        //on récupère l'user à partir du token
        $user = $token->getUser();

        //si l'utilisateur n'est pas connecté -> return false
        if(!$user instanceof UserInterface) return false;

        // user = admin ?
        if($this->security->isGranted('ROLE_ADMIN')) return true;

        // user connecté mais pas admin -> vérifier permissions
        switch ($attribute) {
            case self::EDIT:
                # on vérifie si l'user peut éditer
                return $this->canEdit();
                break;
            case self::DELETE:
                # on vérifie si l'user peut supprimer
                return $this->canDelete();
                break;
        }

    }

    private function canEdit() {
        return $this->security->isGranted('ROLE_PRODUCT_ADMIN');
    }

    private function canDelete() {
        return $this->security->isGranted('ROLE_ADMIN');
    }
}
