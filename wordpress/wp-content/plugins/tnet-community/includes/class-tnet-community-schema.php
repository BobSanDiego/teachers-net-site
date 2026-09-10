<?php
defined('ABSPATH') || exit;

final class TNet_Community_Schema {
    public const VERSION = '6';

    public static function table_names(): array {
        global $wpdb;
        return [
            'posts' => $wpdb->prefix . 'community_posts',
            'audit' => $wpdb->prefix . 'community_post_audit',
            'events' => $wpdb->prefix . 'community_publication_events',
            'communities' => $wpdb->prefix . 'community_communities',
            'memberships' => $wpdb->prefix . 'community_memberships',
            'membership_audit' => $wpdb->prefix . 'community_membership_audit',
            'membership_migrations' => $wpdb->prefix . 'community_membership_migrations',
            'reports' => $wpdb->prefix . 'community_reports',
            'report_audit' => $wpdb->prefix . 'community_report_audit',
            'relationships' => $wpdb->prefix . 'community_relationships',
            'relationship_audit' => $wpdb->prefix . 'community_relationship_audit',
            'preferences' => $wpdb->prefix . 'community_notification_preferences',
            'preference_audit' => $wpdb->prefix . 'community_notification_preference_audit',
            'suppressions' => $wpdb->prefix . 'community_notification_suppressions',
            'preference_reconciliation' => $wpdb->prefix . 'community_notification_preference_reconciliation',
            'notification_decisions' => $wpdb->prefix . 'community_notification_decisions',
        ];
    }

    /**
     * Local, additive storage for historical migration preparation. These
     * tables are deliberately separate from canonical Community publication
     * tables: creating a foundation record never publishes a discussion.
     */
    public static function migration_table_names(): array {
        global $wpdb;
        return [
            'migration_runs' => $wpdb->prefix . 'community_migration_runs',
            'migration_ledger' => $wpdb->prefix . 'community_migration_ledger',
            'migration_audit' => $wpdb->prefix . 'community_migration_audit',
            'board_maps' => $wpdb->prefix . 'community_legacy_board_maps',
            'url_aliases' => $wpdb->prefix . 'community_legacy_url_aliases',
            'exceptions' => $wpdb->prefix . 'community_migration_exceptions',
        ];
    }

    public static function install(): void {
        global $wpdb;
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        $t = self::table_names();
        $c = $wpdb->get_charset_collate();
        dbDelta("CREATE TABLE {$t['posts']} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            post_id VARCHAR(80) NOT NULL,
            community_id VARCHAR(80) NOT NULL,
            author_id VARCHAR(80) NOT NULL,
            thread_id VARCHAR(80) NOT NULL,
            parent_post_id VARCHAR(80) NULL,
            post_type VARCHAR(16) NOT NULL,
            title TEXT NOT NULL,
            body LONGTEXT NOT NULL,
            visibility VARCHAR(24) NOT NULL,
            moderation_state VARCHAR(24) NOT NULL,
            publication_state VARCHAR(24) NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            published_at DATETIME NULL,
            idempotency_key VARCHAR(128) NOT NULL,
            revision INT UNSIGNED NOT NULL DEFAULT 1,
            safe_target VARCHAR(255) NOT NULL,
            compatibility_json LONGTEXT NULL,
            audit_json LONGTEXT NULL,
            conversation_root_id VARCHAR(80) NULL,
            reply_to_post_id VARCHAR(80) NULL,
            reply_to_author_id VARCHAR(80) NULL,
            owner_product VARCHAR(64) NULL,
            subject_type VARCHAR(80) NULL,
            subject_id VARCHAR(128) NULL,
            source_namespace VARCHAR(128) NULL,
            subject_revision VARCHAR(64) NULL,
            PRIMARY KEY (id),
            UNIQUE KEY post_id (post_id),
            UNIQUE KEY submission (community_id, author_id, idempotency_key),
            KEY community_state_time (community_id, publication_state, published_at),
            KEY thread_time_post (thread_id, created_at, post_id),
            KEY parent_post (parent_post_id),
            KEY author_state (author_id, publication_state),
            KEY conversation_root_time (conversation_root_id, created_at, post_id),
            KEY reply_target_state (reply_to_post_id, publication_state),
            KEY subject_identity (owner_product, subject_type, subject_id)
        ) $c;");
        dbDelta("CREATE TABLE {$t['audit']} (
            audit_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            post_id VARCHAR(80) NOT NULL,
            action VARCHAR(64) NOT NULL,
            actor_id VARCHAR(80) NOT NULL,
            reason TEXT NOT NULL,
            previous_state VARCHAR(24) NULL,
            new_state VARCHAR(24) NOT NULL,
            evidence_json LONGTEXT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (audit_id),
            KEY post_audit (post_id, audit_id)
        ) $c;");
        dbDelta("CREATE TABLE {$t['events']} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            event_id VARCHAR(80) NOT NULL,
            event_type VARCHAR(80) NOT NULL,
            post_id VARCHAR(80) NOT NULL,
            community_id VARCHAR(80) NOT NULL,
            thread_id VARCHAR(80) NOT NULL,
            parent_post_id VARCHAR(80) NULL,
            event_version INT UNSIGNED NOT NULL DEFAULT 1,
            payload_json LONGTEXT NOT NULL,
            delivery_status VARCHAR(24) NOT NULL DEFAULT 'pending',
            dedupe_key VARCHAR(128) NOT NULL,
            created_at DATETIME NOT NULL,
            dispatched_at DATETIME NULL,
            PRIMARY KEY (id),
            UNIQUE KEY event_id (event_id),
            UNIQUE KEY dedupe (dedupe_key),
            KEY pending_events (delivery_status, id)
        ) $c;");
        dbDelta("CREATE TABLE {$t['communities']} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            community_id VARCHAR(80) NOT NULL,
            slug VARCHAR(120) NOT NULL,
            display_name VARCHAR(255) NOT NULL,
            lifecycle_state VARCHAR(24) NOT NULL,
            visibility VARCHAR(24) NOT NULL,
            compatibility_json LONGTEXT NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY community_id (community_id),
            UNIQUE KEY slug (slug),
            KEY lifecycle_visibility (lifecycle_state, visibility)
        ) $c;");
        dbDelta("CREATE TABLE {$t['memberships']} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            membership_id VARCHAR(80) NOT NULL,
            community_id VARCHAR(80) NOT NULL,
            user_id VARCHAR(80) NOT NULL,
            state VARCHAR(24) NOT NULL,
            joined_at DATETIME NULL,
            state_changed_at DATETIME NOT NULL,
            source_namespace VARCHAR(128) NULL,
            source_membership_id VARCHAR(80) NULL,
            legacy_group_id VARCHAR(80) NULL,
            provenance_json LONGTEXT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY membership_id (membership_id),
            UNIQUE KEY community_user (community_id, user_id),
            KEY community_state (community_id, state),
            KEY source_membership (source_namespace, source_membership_id)
        ) $c;");
        dbDelta("CREATE TABLE {$t['membership_audit']} (
            audit_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            membership_id VARCHAR(80) NOT NULL,
            action VARCHAR(64) NOT NULL,
            actor_id VARCHAR(80) NOT NULL,
            previous_state VARCHAR(24) NULL,
            new_state VARCHAR(24) NOT NULL,
            reason TEXT NOT NULL,
            evidence_json LONGTEXT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (audit_id),
            KEY membership_audit (membership_id, audit_id)
        ) $c;");
        dbDelta("CREATE TABLE {$t['membership_migrations']} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            source_namespace VARCHAR(128) NOT NULL,
            source_membership_id VARCHAR(80) NOT NULL,
            legacy_group_id VARCHAR(80) NULL,
            legacy_user_id VARCHAR(80) NULL,
            source_state VARCHAR(24) NULL,
            source_checksum CHAR(64) NOT NULL,
            mapping_state VARCHAR(24) NOT NULL,
            identity_state VARCHAR(24) NOT NULL,
            disposition VARCHAR(40) NOT NULL,
            reason_code VARCHAR(128) NULL,
            community_id VARCHAR(80) NULL,
            canonical_user_id VARCHAR(80) NULL,
            target_membership_id VARCHAR(80) NULL,
            run_id VARCHAR(80) NOT NULL,
            rule_version VARCHAR(64) NOT NULL,
            source_snapshot_json LONGTEXT NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY source_membership (source_namespace, source_membership_id),
            KEY migration_disposition (disposition, mapping_state, identity_state),
            KEY target_membership (target_membership_id)
        ) $c;");
        dbDelta("CREATE TABLE {$t['reports']} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            report_id VARCHAR(80) NOT NULL,
            target_post_id VARCHAR(80) NOT NULL,
            target_type VARCHAR(16) NOT NULL,
            community_id VARCHAR(80) NOT NULL,
            reporter_id VARCHAR(80) NOT NULL,
            reason_code VARCHAR(32) NOT NULL,
            note TEXT NULL,
            evidence_json LONGTEXT NULL,
            state VARCHAR(24) NOT NULL,
            idempotency_key VARCHAR(128) NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            resolved_at DATETIME NULL,
            resolved_by VARCHAR(80) NULL,
            resolution_code VARCHAR(40) NULL,
            PRIMARY KEY (id),
            UNIQUE KEY report_id (report_id),
            UNIQUE KEY report_submission (reporter_id, idempotency_key),
            KEY report_queue (state, created_at),
            KEY report_target (target_post_id, state),
            KEY report_community (community_id, state)
        ) $c;");
        dbDelta("CREATE TABLE {$t['report_audit']} (
            audit_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            report_id VARCHAR(80) NOT NULL,
            action VARCHAR(64) NOT NULL,
            actor_id VARCHAR(80) NOT NULL,
            previous_state VARCHAR(24) NULL,
            new_state VARCHAR(24) NOT NULL,
            reason TEXT NOT NULL,
            evidence_json LONGTEXT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (audit_id),
            KEY report_audit (report_id, audit_id)
        ) $c;");
        dbDelta("CREATE TABLE {$t['relationships']} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            relationship_id VARCHAR(96) NOT NULL,
            relationship_type VARCHAR(40) NOT NULL,
            user_id VARCHAR(80) NOT NULL,
            community_id VARCHAR(80) NULL,
            thread_id VARCHAR(80) NULL,
            target_key VARCHAR(191) NOT NULL,
            state VARCHAR(24) NOT NULL,
            provenance_json LONGTEXT NULL,
            created_at DATETIME NOT NULL,
            state_changed_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY relationship_id (relationship_id),
            UNIQUE KEY relationship_target (relationship_type, user_id, target_key),
            KEY community_relationships (community_id, relationship_type, state),
            KEY thread_relationships (thread_id, relationship_type, state),
            KEY user_relationships (user_id, relationship_type, state)
        ) $c;");
        dbDelta("CREATE TABLE {$t['relationship_audit']} (
            audit_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            relationship_id VARCHAR(96) NOT NULL,
            action VARCHAR(48) NOT NULL,
            actor_id VARCHAR(80) NOT NULL,
            previous_state VARCHAR(24) NULL,
            new_state VARCHAR(24) NOT NULL,
            reason VARCHAR(128) NOT NULL,
            provenance_json LONGTEXT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (audit_id),
            KEY relationship_audit (relationship_id, audit_id)
        ) $c;");
        dbDelta("CREATE TABLE {$t['preferences']} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            preference_id VARCHAR(96) NOT NULL,
            user_id VARCHAR(80) NOT NULL,
            category VARCHAR(64) NOT NULL,
            channel VARCHAR(24) NOT NULL,
            frequency VARCHAR(16) NOT NULL,
            state VARCHAR(24) NOT NULL DEFAULT 'explicit',
            source_namespace VARCHAR(128) NULL,
            provenance_json LONGTEXT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY preference_id (preference_id),
            UNIQUE KEY user_preference (user_id, category, channel),
            KEY preference_lookup (category, channel, frequency, state)
        ) $c;");
        dbDelta("CREATE TABLE {$t['preference_audit']} (
            audit_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            preference_id VARCHAR(96) NOT NULL,
            action VARCHAR(48) NOT NULL,
            actor_id VARCHAR(80) NOT NULL,
            previous_frequency VARCHAR(16) NULL,
            new_frequency VARCHAR(16) NOT NULL,
            reason VARCHAR(128) NOT NULL,
            provenance_json LONGTEXT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (audit_id),
            KEY preference_audit (preference_id, audit_id)
        ) $c;");
        dbDelta("CREATE TABLE {$t['suppressions']} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            suppression_id VARCHAR(96) NOT NULL,
            user_id VARCHAR(80) NOT NULL,
            channel VARCHAR(24) NOT NULL,
            scope VARCHAR(64) NOT NULL,
            reason_code VARCHAR(48) NOT NULL,
            state VARCHAR(24) NOT NULL DEFAULT 'active',
            source_namespace VARCHAR(128) NOT NULL,
            evidence_json LONGTEXT NOT NULL,
            effective_at DATETIME NOT NULL,
            lifted_at DATETIME NULL,
            PRIMARY KEY (id),
            UNIQUE KEY suppression_id (suppression_id),
            KEY suppression_lookup (user_id, channel, scope, state),
            KEY suppression_reason (reason_code, state)
        ) $c;");
        dbDelta("CREATE TABLE {$t['preference_reconciliation']} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            reconciliation_id VARCHAR(96) NOT NULL,
            user_id VARCHAR(80) NULL,
            source_namespace VARCHAR(128) NOT NULL,
            source_key VARCHAR(191) NOT NULL,
            source_value_json LONGTEXT NOT NULL,
            classification VARCHAR(40) NOT NULL,
            reason_code VARCHAR(128) NOT NULL,
            rule_version VARCHAR(64) NOT NULL,
            evidence_json LONGTEXT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY reconciliation_id (reconciliation_id),
            UNIQUE KEY reconciliation_source (source_namespace, source_key),
            KEY reconciliation_classification (classification, reason_code)
        ) $c;");
        dbDelta("CREATE TABLE {$t['notification_decisions']} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            decision_id VARCHAR(96) NOT NULL,
            event_id VARCHAR(191) NOT NULL,
            recipient_user_id BIGINT UNSIGNED NOT NULL,
            channel VARCHAR(24) NOT NULL,
            decision VARCHAR(24) NOT NULL,
            reason_code VARCHAR(128) NOT NULL,
            relationship_basis VARCHAR(191) NULL,
            preference_basis VARCHAR(191) NULL,
            suppression_basis VARCHAR(191) NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY decision_id (decision_id),
            UNIQUE KEY event_recipient_channel (event_id, recipient_user_id, channel),
            KEY recipient_decisions (recipient_user_id, channel, decision, created_at),
            KEY event_decisions (event_id, created_at)
        ) $c;");
        self::install_migration_foundation();
        update_option('tnet_community_schema_version', self::VERSION, false);
    }

    public static function install_migration_foundation(): void {
        global $wpdb;
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        $t = self::migration_table_names();
        $c = $wpdb->get_charset_collate();
        dbDelta("CREATE TABLE {$t['migration_runs']} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            run_id VARCHAR(80) NOT NULL,
            rule_version VARCHAR(64) NOT NULL,
            run_state VARCHAR(24) NOT NULL,
            metadata_json LONGTEXT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY run_id (run_id)
        ) $c;");
        dbDelta("CREATE TABLE {$t['board_maps']} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            source_namespace VARCHAR(128) NOT NULL,
            legacy_path_id VARCHAR(80) NOT NULL,
            legacy_local_path VARCHAR(255) NOT NULL,
            legacy_group_id VARCHAR(80) NULL,
            community_id VARCHAR(80) NOT NULL,
            mapping_state VARCHAR(24) NOT NULL,
            evidence_ref VARCHAR(255) NOT NULL,
            mapping_checksum CHAR(64) NOT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY source_path (source_namespace, legacy_path_id),
            KEY community_state (community_id, mapping_state)
        ) $c;");
        dbDelta("CREATE TABLE {$t['migration_ledger']} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            source_namespace VARCHAR(128) NOT NULL,
            legacy_post_id VARCHAR(80) NOT NULL,
            legacy_topic_id VARCHAR(80) NOT NULL,
            legacy_post_type VARCHAR(16) NOT NULL,
            raw_status VARCHAR(32) NULL,
            source_checksum CHAR(64) NOT NULL,
            board_map_id BIGINT UNSIGNED NULL,
            identity_state VARCHAR(32) NOT NULL,
            moderation_state VARCHAR(32) NOT NULL,
            asset_state VARCHAR(32) NOT NULL,
            disposition VARCHAR(64) NOT NULL,
            reason_code VARCHAR(128) NULL,
            target_community_id VARCHAR(80) NULL,
            target_post_id VARCHAR(80) NULL,
            target_thread_id VARCHAR(80) NULL,
            first_run_id VARCHAR(80) NOT NULL,
            rule_version VARCHAR(64) NOT NULL,
            source_snapshot_json LONGTEXT NOT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY source_record (source_namespace, legacy_post_id),
            KEY topic_source (source_namespace, legacy_topic_id),
            KEY disposition_state (disposition, raw_status),
            KEY target_identity (target_community_id, target_thread_id)
        ) $c;");
        dbDelta("CREATE TABLE {$t['migration_audit']} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            source_namespace VARCHAR(128) NOT NULL,
            legacy_post_id VARCHAR(80) NOT NULL,
            run_id VARCHAR(80) NOT NULL,
            action VARCHAR(64) NOT NULL,
            evidence_json LONGTEXT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            KEY source_audit (source_namespace, legacy_post_id, id),
            KEY run_audit (run_id, id)
        ) $c;");
        dbDelta("CREATE TABLE {$t['url_aliases']} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            source_namespace VARCHAR(128) NOT NULL,
            legacy_url_hash CHAR(64) NOT NULL,
            legacy_url TEXT NOT NULL,
            legacy_post_id VARCHAR(80) NOT NULL,
            legacy_topic_id VARCHAR(80) NOT NULL,
            target_community_id VARCHAR(80) NULL,
            target_post_id VARCHAR(80) NULL,
            target_thread_id VARCHAR(80) NULL,
            intended_target_key VARCHAR(160) NOT NULL,
            alias_state VARCHAR(32) NOT NULL,
            route_state VARCHAR(32) NOT NULL,
            verification_state VARCHAR(32) NOT NULL,
            evidence_ref VARCHAR(255) NOT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY source_url (source_namespace, legacy_url_hash),
            KEY source_post (source_namespace, legacy_post_id)
        ) $c;");
        dbDelta("CREATE TABLE {$t['exceptions']} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            source_namespace VARCHAR(128) NOT NULL,
            legacy_post_id VARCHAR(80) NOT NULL,
            exception_code VARCHAR(128) NOT NULL,
            exception_state VARCHAR(32) NOT NULL,
            evidence_json LONGTEXT NULL,
            first_run_id VARCHAR(80) NOT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY source_exception (source_namespace, legacy_post_id, exception_code),
            KEY exception_state (exception_state, exception_code)
        ) $c;");
    }

    public static function remove_migration_foundation(): void {
        global $wpdb;
        foreach (self::migration_table_names() as $table) {
            $wpdb->query("DROP TABLE IF EXISTS {$table}");
        }
    }

    public static function uninstall(): void {
        global $wpdb; foreach (self::table_names() as $table) $wpdb->query("DROP TABLE IF EXISTS {$table}");
        self::remove_migration_foundation();
        delete_option('tnet_community_schema_version');
    }
}
