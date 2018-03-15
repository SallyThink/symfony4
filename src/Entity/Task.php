<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository")
 */
class Task
{
    const STATUS_NEW = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_COMPLETE = 3;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var int $status
     *
     * @ORM\Column(name="status", type="smallint", options={"default" : 0})
     */
    private $status;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tasks")
     */
    private $user;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="task", orphanRemoval=true)
     */
    private $comments;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="task")
     * @ORM\JoinTable(name="user_task")
     */
    private $executors;

    /**
     * Task constructor.
     */
    public function __construct()
    {
        $this->executors = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function getStatusesList() : array
    {
        return [
            self::STATUS_NEW,
            self::STATUS_ACTIVE,
            self::STATUS_COMPLETE,
        ];
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param ArrayCollection $comments
     */
    public function setComments(ArrayCollection $comments)
    {
        $this->comments = $comments;
    }

    /**
     * @param ArrayCollection $executors
     */
    public function setExecutors(ArrayCollection $executors)
    {
        $this->executors = $executors;
    }

    /**
     * @return ArrayCollection
     */
    public function getExecutors()
    {
        return $this->executors;
    }

    /**
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraints('name', [
            new Assert\NotBlank()
        ]);

        $metadata->addPropertyConstraints('description', [
            new Assert\NotBlank(),
            new Assert\Length(['min' => 10])
        ]);

        $metadata->addPropertyConstraints('status', [
            new Assert\NotBlank(),
            (new Assert\Choice([
                'choices' => [
                    self::STATUS_NEW,
                    self::STATUS_ACTIVE,
                    self::STATUS_COMPLETE,
                ],
            ])),
        ]);
    }
}
