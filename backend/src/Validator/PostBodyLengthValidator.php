<?php

namespace App\Validator;


use App\Entity\Post;
use App\Enum\PostType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class PostBodyLengthValidator extends ConstraintValidator
{
    /**
     * @var int
     */
    private int $textPostBodyLength;

    /**
     * @var int
     */
    private int $imagePostBodyLength;

    /**
     * @var int
     */
    private int $videoPostBodyLength;

    /**
     * @var Constraint
     */
    private Constraint $constraint;

    public function __construct(
        int $textPostBodyLength,
        int $imagePostBodyLength,
        int $videoPostBodyLength
    )
    {
        $this->textPostBodyLength = $textPostBodyLength;
        $this->imagePostBodyLength = $imagePostBodyLength;
        $this->videoPostBodyLength = $videoPostBodyLength;
    }

    /**
     * @param $value
     * @param Constraint $constraint
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof Post || empty($value->getBody())) {
            return;
        }
        $this->constraint = $constraint;

        switch ($value->getPostType()) {
            case PostType::TEXT:
                $this->handleBodyLength($value->getBody(), PostType::TEXT->value, $this->textPostBodyLength);
                break;
            case PostType::IMAGE:
                $this->handleBodyLength($value->getBody(), PostType::IMAGE->value, $this->imagePostBodyLength);
                break;
            case PostType::VIDEO:
                $this->handleBodyLength($value->getBody(), PostType::VIDEO->value, $this->videoPostBodyLength);
                break;
        }
    }

    /**
     * @param string $bodyValue
     * @param string $postType
     * @param int $maxLength
     * @return void
     */
    private function handleBodyLength(string $bodyValue, string $postType, int $maxLength): void
    {
        if (strlen($bodyValue) > $maxLength) {
            $this->context->buildViolation($this->constraint->tooPostBodyMessage)
                ->setParameter('%type%', $postType)
                ->setParameter('%max%', $maxLength)
                ->setCode(1657590177)
                ->addViolation();
        }
    }
}