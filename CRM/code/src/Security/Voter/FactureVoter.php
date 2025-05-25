<?php
namespace App\Security\Voter;

use App\Entity\Client;
use App\Entity\Facture;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class FactureVoter extends Voter
{
    public const VIEW = 'FACTURE_VIEW';
    public const EDIT = 'FACTURE_EDIT';
    public const DELETE = 'FACTURE_DELETE';
    public const CREATE = 'FACTURE_CREATE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE, self::CREATE])
            && (
                ($attribute === self::CREATE && $subject instanceof Client)
                || $subject instanceof Facture
            );
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        // ✏️ CREATE vérifie si l'utilisateur peut ajouter une facture à un client
        if ($attribute === self::CREATE && $subject instanceof Client) {
            return $subject->getUtilisateurOwner() === $user;
        }

        // Les autres cas (edit, delete, view) concernent une facture
        if ($subject instanceof Facture) {
            return $subject->getClient()->getUtilisateurOwner() === $user;
        }

        return false;
    }
}
