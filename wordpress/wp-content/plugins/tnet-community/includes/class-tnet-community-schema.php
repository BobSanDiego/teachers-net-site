<?php
defined('ABSPATH') || exit;

final class TNet_Community_Schema {
    public const VERSION = '1';

    public static function table_names(): array {
        global $wpdb;
        return [
            'posts' => $wpdb->prefix . 'community_posts',
            'audit' => $wpdb->prefix . 'community_post_audit',
            'events' => $wpdb->prefix . 'community_publication_events',
        ];
    }

    public static function install(): void {
        global $wpdb;
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        $t = self::table_names();
        $c = $wpdb->get_charset_collate();
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$t['posts']} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, post_id VARCHAR(80) NOT NULL,
            community_id VARCHAR(80) NOT NULL, author_id VARCHAR(80) NOT NULL,
            thread_id VARCHAR(80) NOT NULL, parent_post_id VARCHAR(80) NULL,
            post_type VARCHAR(16) NOT NULL, title TEXT NOT NULL, body LONGTEXT NOT NULL,
            visibility VARCHAR(24) NOT NULL, moderation_state VARCHAR(24) NOT NULL,
            publication_state VARCHAR(24) NOT NULL, created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL, published_at DATETIME NULL,
            idempotency_key VARCHAR(128) NOT NULL, revision INT UNSIGNED NOT NULL DEFAULT 1,
            safe_target VARCHAR(255) NOT NULL, compatibility_json LONGTEXT NULL,
            audit_json LONGTEXT NULL, PRIMARY KEY (id), UNIQUE KEY post_id (post_id),
            UNIQUE KEY submission (community_id, author_id, idempotency_key),
            KEY community_state_time (community_id, publication_state, published_at),
            KEY thread_time_post (thread_id, created_at, post_id), KEY parent_post (parent_post_id),
            KEY author_state (author_id, publication_state)
        ) $c;");
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$t['audit']} (
            audit_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, post_id VARCHAR(80) NOT NULL,
            action VARCHAR(64) NOT NULL, actor_id VARCHAR(80) NOT NULL, reason TEXT NOT NULL,
            previous_state VARCHAR(24) NULL, new_state VARCHAR(24) NOT NULL,
            evidence_json LONGTEXT NULL, created_at DATETIME NOT NULL,
            PRIMARY KEY (audit_id), KEY post_audit (post_id, audit_id)
        ) $c;");
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$t['events']} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, event_id VARCHAR(80) NOT NULL,
            event_type VARCHAR(80) NOT NULL, post_id VARCHAR(80) NOT NULL,
            community_id VARCHAR(80) NOT NULL, thread_id VARCHAR(80) NOT NULL,
            parent_post_id VARCHAR(80) NULL, event_version INT UNSIGNED NOT NULL DEFAULT 1,
            payload_json LONGTEXT NOT NULL, delivery_status VARCHAR(24) NOT NULL DEFAULT 'pending',
            dedupe_key VARCHAR(128) NOT NULL, created_at DATETIME NOT NULL, dispatched_at DATETIME NULL,
            PRIMARY KEY (id), UNIQUE KEY event_id (event_id), UNIQUE KEY dedupe (dedupe_key),
            KEY pending_events (delivery_status, id)
        ) $c;");
        update_option('tnet_community_schema_version', self::VERSION, false);
    }

    public static function uninstall(): void {
        global $wpdb; foreach (self::table_names() as $table) $wpdb->query("DROP TABLE IF EXISTS {$table}");
        delete_option('tnet_community_schema_version');
    }
}
