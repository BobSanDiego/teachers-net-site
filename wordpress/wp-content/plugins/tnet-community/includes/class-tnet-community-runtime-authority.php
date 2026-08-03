<?php
defined('ABSPATH') || exit;

final class TNet_Community_Runtime_Authority {
    public static function is_local(): bool {
        return (defined('DDEV_PROJECT') && DDEV_PROJECT === 'teachers-net-community3') || getenv('DDEV_PROJECT') === 'teachers-net-community3';
    }

    public static function facts(): array {
        $plugin = realpath(dirname(__DIR__)) ?: dirname(__DIR__);
        $project = (string) (getenv('DDEV_PROJECT') ?: (defined('DDEV_PROJECT') ? DDEV_PROJECT : ''));
        $expectedHost = (string) (getenv('C3_AUTHORITY_HOSTNAME') ?: 'teachers-net-community3.ddev.site');
        $expectedProject = (string) (getenv('C3_AUTHORITY_PROJECT') ?: 'teachers-net-community3');
        $worktree = (string) (getenv('C3_AUTHORITY_WORKTREE') ?: '/home/bobreap/projects/teachers-net-community3');
        $record = self::authority_record();
        $branch = (string) ($record['branch'] ?? getenv('C3_AUTHORITY_BRANCH') ?: self::git('rev-parse --abbrev-ref HEAD'));
        $commit = (string) ($record['commit'] ?? getenv('C3_AUTHORITY_COMMIT') ?: self::git('rev-parse HEAD'));
        $hash = self::tree_hash($plugin);
        $facts = [
            'hostname' => strtolower((string) ($_SERVER['HTTP_HOST'] ?? '')),
            'canonical_hostname' => $expectedHost,
            'ddev_project' => $project,
            'expected_ddev_project' => $expectedProject,
            'ddev_filesystem_path' => '/var/www/html',
            'mounted_plugin_path' => $plugin,
            'authoritative_worktree_path' => $worktree,
            'git_branch' => $branch,
            'git_commit' => $commit,
            'plugin_tree_hash' => $hash,
            'expected_plugin_tree_hash' => (string) ($record['plugin_tree_hash'] ?? getenv('C3_AUTHORITY_PLUGIN_HASH') ?: ''),
            'route' => self::route(),
            'controller' => self::controller(),
            'runtime_timestamp' => gmdate('c'),
        ];
        $facts['status'] = self::matches($facts) ? 'ok' : 'mismatch';
        return $facts;
    }

    public static function header(): void {
        if (!self::is_local()) return;
        $facts = self::facts();
        header('X-TNet-Community-Runtime: '.wp_json_encode($facts));
        header('X-TNet-Community-Runtime-Status: '.$facts['status']);
    }

    public static function badge(array $facts): string {
        $label = $facts['status'] === 'ok' ? 'LOCAL COMMUNITY3 RUNTIME' : 'RUNTIME AUTHORITY MISMATCH';
        $class = $facts['status'] === 'ok' ? 'ok' : 'mismatch';
        $attrs = '';
        foreach ($facts as $key => $value) $attrs .= ' data-runtime-'.esc_attr($key).'="'.esc_attr((string) $value).'"';
        $rows = '';
        foreach ([
            'canonical_hostname' => 'Host', 'ddev_project' => 'DDEV project',
            'ddev_filesystem_path' => 'DDEV path', 'mounted_plugin_path' => 'Mounted plugin',
            'authoritative_worktree_path' => 'Authority worktree', 'git_branch' => 'Branch',
            'git_commit' => 'Commit', 'plugin_tree_hash' => 'Plugin tree',
            'route' => 'Route', 'controller' => 'Controller', 'runtime_timestamp' => 'UTC runtime',
        ] as $key => $labelText) $rows .= '<span><b>'.esc_html($labelText).':</b> '.esc_html((string) ($facts[$key] ?? '')).'</span>';
        return '<aside class="c3-runtime-badge '.$class.'"'.$attrs.' role="status"><strong>'.esc_html($label).'</strong><div>'.$rows.'</div></aside>';
    }

    private static function matches(array $facts): bool {
        return $facts['hostname'] === strtolower($facts['canonical_hostname']) && $facts['ddev_project'] === $facts['expected_ddev_project'] && $facts['git_branch'] === 'COMMUNITY3-ui-working' && preg_match('/^[a-f0-9]{40}$/', $facts['git_commit']) === 1 && $facts['plugin_tree_hash'] === $facts['expected_plugin_tree_hash'];
    }

    private static function git(string $args): string {
        $root = defined('ABSPATH') ? rtrim(ABSPATH, '/') . '/..' : '';
        $value = $root ? shell_exec('git -C '.escapeshellarg($root).' '.$args.' 2>/dev/null') : '';
        return trim((string) $value);
    }

    private static function authority_record(): array {
        $path = defined('ABSPATH') ? rtrim(ABSPATH, '/') . '/../.ddev/runtime-authority.json' : '';
        if (!$path || !is_readable($path)) return [];
        $record = json_decode((string) file_get_contents($path), true);
        return is_array($record) ? $record : [];
    }

    private static function tree_hash(string $path): string {
        $files = [];
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS));
        foreach ($iterator as $file) if ($file->isFile()) $files[] = $file->getPathname();
        sort($files);
        $ctx = hash_init('sha256');
        foreach ($files as $file) { hash_update($ctx, str_replace($path.'/', '', $file).'\0'); hash_update_file($ctx, $file); }
        return hash_final($ctx);
    }

    private static function route(): string {
        if (get_query_var('tnet_community_landing')) return '/community/';
        if (get_query_var('tnet_community_topic_composer')) return '/community/new/';
        if (get_query_var('tnet_community_thread')) return '/community/thread/';
        return (string) ($_SERVER['REQUEST_URI'] ?? '');
    }

    private static function controller(): string {
        if (get_query_var('tnet_community_landing')) return 'TNet_Community_Landing_Controller';
        if (get_query_var('tnet_community_topic_composer')) return 'TNet_Community_Topic_Composer_Controller';
        if (get_query_var('tnet_community_thread')) return 'TNet_Community_Thread_Controller';
        return 'unknown';
    }
}
