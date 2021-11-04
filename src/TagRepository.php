<?php

namespace Bclib\GetBooksFromAlma;

use Bclib\GetBooksFromAlma\Models\Tag;

class TagRepository
{
    /** @var Tag[] */
    private array $title_tags;

    /** @var Tag[] */
    private array $personal_name_tags;

    /** @var Tag[] */
    private array $corporate_name_tags;

    /** @var Tag[] */
    private array $conference_name_tags;

    public function __construct()
    {
        $this->title_tags = [new Tag('245'), new Tag('246'), new Tag('130'), new Tag('240')];
        $this->personal_name_tags = [new Tag('100'), new Tag('700')];
        $this->corporate_name_tags = [new Tag('110'), new Tag('710')];
        $this->conference_name_tags = [new Tag('111')];
    }

    /**
     * @return Tag[]
     */
    public function getTitleTags(): array
    {
        return $this->title_tags;
    }

    /**
     * @return Tag[]
     */
    public function getPersonalNameTags(): array
    {
        return $this->personal_name_tags;
    }

    /**
     * @return Tag[]
     */
    public function getCorporateNameTags(): array
    {
        return $this->corporate_name_tags;
    }

    /**
     * @return Tag[]
     */
    public function getConferenceNameTags(): array
    {
        return $this->conference_name_tags;
    }
}