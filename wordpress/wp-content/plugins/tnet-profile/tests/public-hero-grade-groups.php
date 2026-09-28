<?php
/** Governed Grade hierarchy projection for the compact public hero. */
define('ABSPATH', __DIR__ . '/');
function absint($value) { return abs((int) $value); }
function get_option($name, $default = []) { return ['grade_level' => ['version_id' => 3, 'view_id' => 2]]; }
function is_wp_error($value) { return false; }
final class TNet_Profile_Member_Context { const CORE_TERMS_FRAMEWORK = 'teachers-net'; }
final class CFM {
  public static function get_terms($framework) {
    $rows = [
      ['axis', 'Grade Level', 'axis'],
      ['early', 'Early Childhood', 'axis'],
      ['elem', 'Elementary', 'axis'],
      ['middle', 'Middle School', 'axis'],
      ['high', 'High School', 'axis'],
      ['adult', 'Adult Education', 'axis'],
      ['higher', 'Higher Education', 'axis'],
      ['g1', 'Grade 1', 'elem'], ['g2', 'Grade 2', 'elem'], ['g3', 'Grade 3', 'elem'],
      ['g6', 'Grade 6', 'middle'], ['g7', 'Grade 7', 'middle'], ['g8', 'Grade 8', 'middle'],
    ];
    return array_map(static function ($row) {
      return (object) ['term_uuid' => $row[0], 'label' => $row[1], 'short_label' => str_replace('Grade ', 'Gr ', $row[1]), 'parent_uuid' => $row[2], 'axis_uuid' => 'axis'];
    }, $rows);
  }
}
final class CFM_Views_Service {
  public static function get_published_list($version) {
    return [
      'parent' => ['key' => 'axis', 'framework' => 'teachers-net'],
      'view' => ['id' => 2], 'role' => '',
      'members' => array_map(static function ($uuid) { return ['term_uuid' => $uuid]; },
        ['early', 'elem', 'middle', 'high', 'adult', 'higher', 'g1', 'g2', 'g3', 'g6', 'g7', 'g8']),
    ];
  }
}
require dirname(__DIR__) . '/includes/class-tnet-profile-basics.php';
function expect($condition, $message) { if (!$condition) throw new RuntimeException($message); }

$groups = TNet_Profile_Basics::project_grade_hero_groups(['g6', 'g7', 'g8', 'adult', 'higher']);
expect(array_column($groups, 'label') === ['Middle School', 'Adult Education', 'Higher Education'], 'only represented parents in pedagogical order');
expect($groups[0]['children'] === ['Gr 6', 'Gr 7', 'Gr 8'], 'child-only facts retain governed compact child labels under their displayed parent');
expect($groups[1]['label'] === 'Adult Education' && $groups[2]['label'] === 'Higher Education', 'collapsed hero keeps full governed parent labels');
expect($groups[1]['children'] === [] && $groups[2]['children'] === [], 'top-level direct choices have no invented children');
$parent_only = TNet_Profile_Basics::project_grade_hero_groups(['elem']);
expect(count($parent_only) === 1 && $parent_only[0]['label'] === 'Elementary' && $parent_only[0]['children'] === [], 'parent-only selection stays parent-only');
$child_only = TNet_Profile_Basics::project_grade_hero_groups(['g1']);
expect(count($child_only) === 1 && $child_only[0]['label'] === 'Elementary' && $child_only[0]['children'] === ['Gr 1'], 'child-only selection derives display parent and governed compact child label');
$partial = TNet_Profile_Basics::project_grade_hero_groups(['g6', 'g7']);
expect($partial[0]['children'] === ['Gr 6', 'Gr 7'], 'partial parent lists selected compact child labels only');
expect(TNet_Profile_Basics::project_grade_hero_groups([]) === [], 'no selected facts produce no hero groups');
echo "PASS governed Grade hero groups, parent-only and child-only projection\n";
