<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AvatarRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass=AvatarRepository::class)
 */
class Avatar
{

    const DEFAULT_IMG_URL = '/img/users/default.png';
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $url = '/img/users/default.png';

    private ?UploadedFile $file;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="avatar", cascade={"persist", "remove"})
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file ?? null;
    }

    public function setFile(UploadedFile $file): self
    {
        $this->file = $file;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
