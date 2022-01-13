<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GroupRepository::class)
 * @ORM\Table(name="`group`")
 */
class Group
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $groupe_name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroupeName(): ?string
    {
        return $this->groupe_name;
    }

    public function setGroupeName(string $groupe_name): self
    {
        $this->groupe_name = $groupe_name;

        return $this;
    }
}
