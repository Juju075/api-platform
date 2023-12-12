<?php

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use symfony\Component\String\Slugger\SluggerInterface;
#[UniqueEntity('slug')]
trait SlugTrait
{

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Groups(['read:collection', 'write:Post'])]
    private string $slug;

    #[ORM\PrePersist]
    private function generateSlug(SluggerInterface $slugger): void
    {
        if ($this->title) {
            $slugger->slug($this->title)->lower();
        }
        //erreur title missing
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }
}

