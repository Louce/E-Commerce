<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

trait CreatedAtTrait {
    #[ORM\Column(type: Types::DATETIME_MUTABLE,  options:['default' => 'CURRENT_TIMESTAMP'])]
    private $created_at;
    
    public function getCreatedAt(): ?\DateTimeImmutable
        {
            return $this->created_at;
        }
    
        public function setCreatedAt(\DateTimeImmutable $created_at): self
        {
            $this->created_at = $created_at;
    
            return $this;
        }
}
