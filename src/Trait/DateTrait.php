<?php

namespace App\Trait;

use Doctrine\ORM\Mapping as ORM;

trait DateTrait
{
    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $created_at;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updated_at = null;

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $$updated_at;

        return $this;
    }

    /**
     * set createdAt value before insert
     */
    #[ORM\PrePersist]
    public function prePersist()
    {
        $this->created_at = new \DateTime();
    }

    /**
     * set updatedAt value before update
     */
    #[ORM\PreUpdate]
    public function setUpdatedAtValue()
    {
        $this->updated_at = new \DateTime();
    }
}