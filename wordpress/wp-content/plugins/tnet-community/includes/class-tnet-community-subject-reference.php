<?php
defined('ABSPATH') || exit;

final class TNet_Community_Subject_Reference {
    private const PAIRS = [
        'community' => ['community_topic'],
        'lesson-bank' => ['lesson'],
        'teachers-net' => ['article'],
    ];
    private function __construct(private string $owner_product, private string $subject_type, private string $subject_id, private ?string $source_namespace = null, private ?string $subject_revision = null) {}
    public static function from_array(array $value): self {
        $owner = trim((string)($value['owner_product'] ?? ''));
        $type = trim((string)($value['subject_type'] ?? ''));
        $id = trim((string)($value['subject_id'] ?? ''));
        if (!isset(self::PAIRS[$owner]) || !in_array($type, self::PAIRS[$owner], true) || $id === '' || !preg_match('/^[A-Za-z0-9][A-Za-z0-9._:-]{0,127}$/', $id)) throw new InvalidArgumentException('SUBJECT_REFERENCE_INVALID');
        $source = self::optional_token($value['source_namespace'] ?? null);
        $revision = self::optional_token($value['subject_revision'] ?? null);
        return new self($owner, $type, $id, $source, $revision);
    }
    public static function for_topic(string $post_id): self { return self::from_array(['owner_product'=>'community','subject_type'=>'community_topic','subject_id'=>$post_id]); }
    private static function optional_token(mixed $value): ?string { if ($value === null || $value === '') return null; $value=trim((string)$value); if (!preg_match('/^[A-Za-z0-9][A-Za-z0-9._:-]{0,127}$/',$value)) throw new InvalidArgumentException('SUBJECT_REFERENCE_INVALID'); return $value; }
    public function to_array(): array { return ['owner_product'=>$this->owner_product,'subject_type'=>$this->subject_type,'subject_id'=>$this->subject_id,'source_namespace'=>$this->source_namespace,'subject_revision'=>$this->subject_revision]; }
}
