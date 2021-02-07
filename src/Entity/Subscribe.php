<?php

namespace App\Entity;

use App\Repository\SubscribeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Count;

/**
 * @ORM\Entity(repositoryClass=SubscribeRepository::class)
 * @ORM\Table(name="`subscribe`")
 */
class Subscribe
{
    public $name;
    public $email;
    public $id;
    public $data;
    /**
     * @Count(min = 1, minMessage = "At least one item must be selected")
     */
    public $news;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getNews(): array
    {
        return $this->news;
    }

    public function setNews(array $news): void
    {
        $this->news = $news;
    }

    public function getId(int $id): ?int
    {
        return $this->id;
    }
    public function setId(int $id): ?int
    {
        return $this->id = $id;
    }

    public function getData(array $data): array
    {
        return $this->data;
    }
    public function setData(array $data): array
    {
        return $this->data = $data;
    }
}