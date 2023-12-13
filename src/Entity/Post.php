<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\ApiResource;

use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Serializer\Filter\GroupFilter;
use App\Controller\customController;
use App\Entity\Traits\SlugTrait;
use App\Entity\Traits\Timestampable;
use App\Repository\PostRepository;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Valid;

//TODO precise les context dans les verbs
//'normalizationContext' => ['groups' => ['read:collection','read:item','read:Post']
//put   'denormalization_context' => ['groups' => ['write:Post']
//Enderpoint configure au maximum
#[ORM\Entity(repositoryClass: PostRepository::class)]
//Getcollection 'validation_groups'=>[]
#[ORM\HasLifecycleCallbacks]
#[
    ApiResource(
        //Security is_granted PostVoter > Admin Author or just Connected
        //groups "admin" "author" "users"
        //seul les admin peuvent delete
        operations: [
            new GetCollection(),
            new Get(),
            new \ApiPlatform\Metadata\Post
            (
                openapiContext: [
                    'summary' => 'ici titre',
                    'requestBody' => [
                        'content' =>
                            [
                                'application/ld+json' =>
                                    [
                                        'example' =>
                                            [
                                                "title" => "string",
                                                "slug" => "example",
                                                "content" => "string",
                                                "createdAt" => "2023-12-02T12:09:25.513Z",
                                                "category"=>"/api/categories/1"
                                            ],
                                        'schema' =>
                                            [
                                                "@context"=>"a preciser",
                                                "@id"=>"integer",
                                                "@type"=>"a preciser",
                                                "title" => "string",
                                                "slug" => "example",
                                                "content" => "string",
                                                "createdAt" => "2023-12-02T12:09:25.513Z",
                                                "category"=>"/api/categories/1"
                                            ]
                                    ]
                            ]
                    ],
                ]
            ),
            new Delete(),
            new Patch(security: "is_granted('ROLE_ADMIN') or object.owner == user"),
            new Put
            (
                controller: customController::class
            )
        ],
        normalizationContext: ['groups' => ['read:collection']],
        validationContext: ['groups' => ['write:Post']],
        paginationItemsPerPage: 2),

]
//Trie order  asc et desc de  id et title
#[ApiFilter(OrderFilter::class,properties: ['id','title'])]

//Trie order asc et desc de title string
#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial'])]

//createdAt[before]  createdAt[strictly_before] format en string
//createdAt[after] ... createdAt[strictly_after]
#[ApiFilter(DateFilter::class,properties: ['createdAt'=>'after'])]

//Trie de group argument
#[ApiFilter(GroupFilter::class,
    arguments:
    [
        'parameterName'=>'demo'
    ]
)]
class Post

{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:collection'])]
    private ?int $id = null;

    // min 5 caractere uniquement si creation d'un post  start end date filter
    #[ORM\Column(length: 255)]
    #[
        Groups(['read:collection']),
        Length(min: 5, groups: ['create:Post']),
        ApiProperty(openapiContext: ['type' => '', 'description' => ['']])
    ]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read:collection'])]
    private ?string $content = null;

    //doit etre valide avant de continuer
    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'posts')]
    #[
        Groups(['read:item', 'write:Post']),
        valid()
    ]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    private ?User $User = null;


    public function validationGroups(self $post)
    {
        return ['create:Post'];
    }

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTime();

    }

    use SlugTrait;
    use Timestampable;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getPriority()
    {
        //
    }
//    public static function validationGroups(self $post): array
//    {
//        return ['create:Post'];
//    }

public function getUser(): ?User
{
    return $this->User;
}

public function setUser(?User $User): static
{
    $this->User = $User;

    return $this;
}
}
