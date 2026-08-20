<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DatabaseRelationController extends Controller
{
    /**
     * Modul grouping configuration with color and icon
     */
    protected array $moduleConfig = [
        'work_order' => [
            'name' => 'Work Order & Maintenance',
            'color' => '#f59e0b', // Amber
            'badge_bg' => 'rgba(245, 158, 11, 0.2)',
            'tables' => [
                'work_orders', 'wo_tasks', 'wo_subtasks', 'wo_subtask_parts', 
                'wo_subtask_manpower', 'wo_subtask_tools', 'wo_comments', 'wo_categories',
                'pra_work_orders', 'jwos', 'pm_schedules', 'pm_schedule_histories',
                'pm_templates', 'pm_template_tasks', 'pm_template_subtasks', 
                'pm_template_subtask_parts', 'breakdown_types', 'component_groups',
                'plan_budgets', 'plan_budget_parts', 'plan_budget_units'
            ]
        ],
        'asset_unit' => [
            'name' => 'Asset & Unit Fleet',
            'color' => '#06b6d4', // Cyan
            'badge_bg' => 'rgba(6, 182, 212, 0.2)',
            'tables' => [
                'master_units', 'unit_models', 'unit_types', 'hour_meters', 'sites'
            ]
        ],
        'inventory_part' => [
            'name' => 'Parts & Inventory',
            'color' => '#10b981', // Emerald
            'badge_bg' => 'rgba(16, 185, 129, 0.2)',
            'tables' => [
                'parts', 'part_categories', 'part_unit_models', 'vendors'
            ]
        ],
        'toolroom' => [
            'name' => 'ToolRoom & Peralatan',
            'color' => '#eab308', // Yellow
            'badge_bg' => 'rgba(234, 179, 8, 0.2)',
            'tables' => [
                'tools', 'tool_categories', 'tool_stocks', 'tool_transactions',
                'tool_stock_requests', 'tool_stock_request_items', 'stock_opnames',
                'stock_opname_details', 'incident_reports', 'mechanics'
            ]
        ],
        'hse_safety' => [
            'name' => 'HSE & Safety',
            'color' => '#ef4444', // Red
            'badge_bg' => 'rgba(239, 68, 68, 0.2)',
            'tables' => [
                'hse_jsas', 'hse_jsa_steps', 'hse_lotos', 'hse_ptws',
                'document_signatures', 'fars', 'far_attachments'
            ]
        ],
        'production' => [
            'name' => 'Operasional Produksi',
            'color' => '#f97316', // Orange
            'badge_bg' => 'rgba(249, 115, 22, 0.2)',
            'tables' => [
                'productions', 'production_fleets', 'production_haulers',
                'production_supports', 'production_delays'
            ]
        ],
        'meeting_action' => [
            'name' => 'Notulen & Action Items',
            'color' => '#ec4899', // Pink
            'badge_bg' => 'rgba(236, 72, 153, 0.2)',
            'tables' => [
                'meetings', 'meeting_action_items', 'meeting_action_item_logs'
            ]
        ],
        'auth_access' => [
            'name' => 'User & Hak Akses',
            'color' => '#8b5cf6', // Violet
            'badge_bg' => 'rgba(139, 92, 246, 0.2)',
            'tables' => [
                'users', 'roles', 'permissions', 'model_has_roles',
                'model_has_permissions', 'role_has_permissions',
                'jabatans', 'departments', 'modules', 'approval_matrices'
            ]
        ],
        'system_core' => [
            'name' => 'Sistem & Log',
            'color' => '#64748b', // Slate
            'badge_bg' => 'rgba(100, 116, 139, 0.2)',
            'tables' => [
                'activity_log', 'messages', 'notifications', 'app_settings',
                'failed_jobs', 'personal_access_tokens', 'migrations',
                'password_reset_tokens', 'sessions'
            ]
        ]
    ];

    /**
     * Render the 3D Schema Visualizer view
     */
    public function index()
    {
        return view('database-relations.index');
    }

    /**
     * Get Schema Data (Nodes & Links) as JSON
     */
    public function getSchemaData()
    {
        $dbConnection = config('database.default');
        $dbName = config("database.connections.{$dbConnection}.database");

        $rawTables = [];
        $rawColumns = [];
        $foreignKeyConstraints = [];

        try {
            // MySQL/MariaDB information_schema
            if (in_array(config("database.connections.{$dbConnection}.driver"), ['mysql', 'mariadb'])) {
                $rawTables = DB::select("
                    SELECT TABLE_NAME, TABLE_COMMENT, TABLE_ROWS, DATA_LENGTH, INDEX_LENGTH
                    FROM information_schema.TABLES
                    WHERE TABLE_SCHEMA = ? AND TABLE_TYPE = 'BASE TABLE'
                    ORDER BY TABLE_NAME
                ", [$dbName]);

                $rawColumns = DB::select("
                    SELECT TABLE_NAME, COLUMN_NAME, DATA_TYPE, COLUMN_TYPE, IS_NULLABLE, 
                           COLUMN_KEY, COLUMN_DEFAULT, COLUMN_COMMENT, EXTRA
                    FROM information_schema.COLUMNS
                    WHERE TABLE_SCHEMA = ?
                    ORDER BY TABLE_NAME, ORDINAL_POSITION
                ", [$dbName]);

                $foreignKeyConstraints = DB::select("
                    SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, 
                           REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
                    FROM information_schema.KEY_COLUMN_USAGE
                    WHERE TABLE_SCHEMA = ? AND REFERENCED_TABLE_NAME IS NOT NULL
                ", [$dbName]);
            }
        } catch (\Throwable $e) {
            // Fallback for other drivers or if information_schema query fails
        }

        // Map column data by table
        $columnsByTable = [];
        foreach ($rawColumns as $col) {
            $tableName = $col->TABLE_NAME;
            if (!isset($columnsByTable[$tableName])) {
                $columnsByTable[$tableName] = [];
            }
            $columnsByTable[$tableName][] = [
                'name' => $col->COLUMN_NAME,
                'type' => $col->DATA_TYPE,
                'full_type' => $col->COLUMN_TYPE,
                'is_pk' => ($col->COLUMN_KEY === 'PRI'),
                'is_fk' => ($col->COLUMN_KEY === 'MUL' || Str::endsWith($col->COLUMN_NAME, '_id')),
                'nullable' => ($col->IS_NULLABLE === 'YES'),
                'default' => $col->COLUMN_DEFAULT,
                'comment' => $col->COLUMN_COMMENT ?? '',
                'extra' => $col->EXTRA ?? ''
            ];
        }

        // List of all table names
        $tableNames = [];
        $tableMeta = [];
        if (!empty($rawTables)) {
            foreach ($rawTables as $t) {
                $tableNames[] = $t->TABLE_NAME;
                $tableMeta[$t->TABLE_NAME] = [
                    'rows' => (int) ($t->TABLE_ROWS ?? 0),
                    'comment' => $t->TABLE_COMMENT ?? '',
                    'size_bytes' => (int) (($t->DATA_LENGTH ?? 0) + ($t->INDEX_LENGTH ?? 0))
                ];
            }
        } else {
            // Fallback
            $tableNames = Schema::getTableListing();
            foreach ($tableNames as $t) {
                $tableMeta[$t] = [
                    'rows' => 0,
                    'comment' => '',
                    'size_bytes' => 0
                ];
            }
        }

        // Build Nodes
        $nodes = [];
        $nodeMap = [];
        $moduleStats = [];

        foreach ($tableNames as $table) {
            $moduleInfo = $this->determineModule($table);
            $cols = $columnsByTable[$table] ?? [];
            
            // If columns weren't fetched from information_schema, fallback to Schema
            if (empty($cols)) {
                try {
                    $columnListing = Schema::getColumnListing($table);
                    foreach ($columnListing as $cName) {
                        $cols[] = [
                            'name' => $cName,
                            'type' => 'varchar',
                            'full_type' => 'varchar',
                            'is_pk' => ($cName === 'id'),
                            'is_fk' => Str::endsWith($cName, '_id'),
                            'nullable' => true,
                            'default' => null,
                            'comment' => '',
                            'extra' => ''
                        ];
                    }
                } catch (\Throwable $e) {}
            }

            // Estimate or get actual rows count if 0
            $rowCount = $tableMeta[$table]['rows'] ?? 0;
            if ($rowCount === 0) {
                try {
                    $rowCount = DB::table($table)->count();
                } catch (\Throwable $e) {}
            }

            $pkColumns = array_values(array_filter($cols, fn($c) => $c['is_pk']));
            $fkColumns = array_values(array_filter($cols, fn($c) => $c['is_fk']));

            $node = [
                'id' => $table,
                'name' => $this->formatTableTitle($table),
                'raw_name' => $table,
                'module_key' => $moduleInfo['key'],
                'module_name' => $moduleInfo['name'],
                'color' => $moduleInfo['color'],
                'badge_bg' => $moduleInfo['badge_bg'],
                'rows' => $rowCount,
                'comment' => $tableMeta[$table]['comment'] ?? '',
                'size_formatted' => $this->formatBytes($tableMeta[$table]['size_bytes'] ?? 0),
                'columns_count' => count($cols),
                'pk_count' => count($pkColumns),
                'fk_count' => count($fkColumns),
                'columns' => $cols,
                // Default size value for 3D sphere
                'val' => max(15, min(60, count($cols) * 2 + ($rowCount > 0 ? log($rowCount + 1) * 3 : 0)))
            ];

            $nodes[] = $node;
            $nodeMap[$table] = $node;

            // Module counter
            if (!isset($moduleStats[$moduleInfo['key']])) {
                $moduleStats[$moduleInfo['key']] = [
                    'key' => $moduleInfo['key'],
                    'name' => $moduleInfo['name'],
                    'color' => $moduleInfo['color'],
                    'count' => 0
                ];
            }
            $moduleStats[$moduleInfo['key']]['count']++;
        }

        // Build Relationships (Links)
        $links = [];
        $existingLinks = [];

        // 1. Explicit DB Foreign Keys
        foreach ($foreignKeyConstraints as $fk) {
            $source = $fk->TABLE_NAME;
            $target = $fk->REFERENCED_TABLE_NAME;
            $fkCol = $fk->COLUMN_NAME;
            $pkCol = $fk->REFERENCED_COLUMN_NAME;

            if (isset($nodeMap[$source]) && isset($nodeMap[$target])) {
                $linkKey = "{$source}->{$target}:{$fkCol}";
                if (!isset($existingLinks[$linkKey])) {
                    $existingLinks[$linkKey] = true;
                    $links[] = [
                        'source' => $source,
                        'target' => $target,
                        'foreign_key' => $fkCol,
                        'primary_key' => $pkCol,
                        'type' => 'explicit',
                        'label' => "{$source}.{$fkCol} ➔ {$target}.{$pkCol}",
                        'color' => $nodeMap[$source]['color']
                    ];
                }
            }
        }

        // 2. Intelligent Heuristics for Implicit Laravel Foreign Keys
        $tableListSet = array_flip($tableNames);
        foreach ($nodes as $node) {
            $source = $node['id'];
            foreach ($node['columns'] as $col) {
                $colName = $col['name'];

                // Check for standard conventions
                $target = null;
                $pkCol = 'id';

                if ($colName === 'user_id' || $colName === 'created_by' || $colName === 'updated_by' || $colName === 'mechanic_user_id' || $colName === 'assigned_to' || $colName === 'approved_by') {
                    if (isset($tableListSet['users'])) $target = 'users';
                } elseif ($colName === 'site_id') {
                    if (isset($tableListSet['sites'])) $target = 'sites';
                } elseif ($colName === 'department_id') {
                    if (isset($tableListSet['departments'])) $target = 'departments';
                } elseif ($colName === 'jabatan_id') {
                    if (isset($tableListSet['jabatans'])) $target = 'jabatans';
                } elseif ($colName === 'master_unit_id') {
                    if (isset($tableListSet['master_units'])) $target = 'master_units';
                } elseif ($colName === 'unit_model_id') {
                    if (isset($tableListSet['unit_models'])) $target = 'unit_models';
                } elseif ($colName === 'unit_type_id') {
                    if (isset($tableListSet['unit_types'])) $target = 'unit_types';
                } elseif ($colName === 'work_order_id' || $colName === 'wo_id') {
                    if (isset($tableListSet['work_orders'])) $target = 'work_orders';
                } elseif ($colName === 'pra_work_order_id') {
                    if (isset($tableListSet['pra_work_orders'])) $target = 'pra_work_orders';
                } elseif ($colName === 'jwo_id') {
                    if (isset($tableListSet['jwos'])) $target = 'jwos';
                } elseif ($colName === 'pm_schedule_id') {
                    if (isset($tableListSet['pm_schedules'])) $target = 'pm_schedules';
                } elseif ($colName === 'pm_template_id') {
                    if (isset($tableListSet['pm_templates'])) $target = 'pm_templates';
                } elseif ($colName === 'part_id') {
                    if (isset($tableListSet['parts'])) $target = 'parts';
                } elseif ($colName === 'part_category_id') {
                    if (isset($tableListSet['part_categories'])) $target = 'part_categories';
                } elseif ($colName === 'tool_id') {
                    if (isset($tableListSet['tools'])) $target = 'tools';
                } elseif ($colName === 'tool_category_id') {
                    if (isset($tableListSet['tool_categories'])) $target = 'tool_categories';
                } elseif ($colName === 'vendor_id') {
                    if (isset($tableListSet['vendors'])) $target = 'vendors';
                } elseif ($colName === 'meeting_id') {
                    if (isset($tableListSet['meetings'])) $target = 'meetings';
                } elseif ($colName === 'production_id') {
                    if (isset($tableListSet['productions'])) $target = 'productions';
                } elseif ($colName === 'parent_id') {
                    $target = $source; // Self-referencing
                } elseif (Str::endsWith($colName, '_id')) {
                    // Try pluralizing the base
                    $base = substr($colName, 0, -3);
                    $plural = Str::plural($base);
                    if (isset($tableListSet[$plural])) {
                        $target = $plural;
                    } elseif (isset($tableListSet[$base])) {
                        $target = $base;
                    }
                }

                if ($target && isset($nodeMap[$target])) {
                    $linkKey = "{$source}->{$target}:{$colName}";
                    if (!isset($existingLinks[$linkKey])) {
                        $existingLinks[$linkKey] = true;
                        $links[] = [
                            'source' => $source,
                            'target' => $target,
                            'foreign_key' => $colName,
                            'primary_key' => $pkCol,
                            'type' => 'implicit',
                            'label' => "{$source}.{$colName} ➔ {$target}.{$pkCol}",
                            'color' => $nodeMap[$source]['color']
                        ];
                    }
                }
            }
        }

        // Recalculate node degrees / connection count
        $degrees = [];
        foreach ($links as $link) {
            $src = is_array($link['source']) ? $link['source']['id'] : $link['source'];
            $tgt = is_array($link['target']) ? $link['target']['id'] : $link['target'];
            $degrees[$src] = ($degrees[$src] ?? 0) + 1;
            $degrees[$tgt] = ($degrees[$tgt] ?? 0) + 1;
        }

        foreach ($nodes as &$n) {
            $connCount = $degrees[$n['id']] ?? 0;
            $n['connections_count'] = $connCount;
            // Enhanced 3D sphere volume
            $n['val'] = 14 + ($connCount * 3.5) + min(20, count($n['columns']));
        }

        // Summary Statistics
        $totalColumns = array_sum(array_column($nodes, 'columns_count'));
        $totalRows = array_sum(array_column($nodes, 'rows'));

        return response()->json([
            'success' => true,
            'stats' => [
                'total_tables' => count($nodes),
                'total_links' => count($links),
                'total_columns' => $totalColumns,
                'total_rows' => $totalRows
            ],
            'modules' => array_values($moduleStats),
            'nodes' => $nodes,
            'links' => $links
        ]);
    }

    /**
     * Get sample rows from a table for quick preview
     */
    public function getTableSample(string $table)
    {
        $validTables = Schema::getTableListing();
        if (!in_array($table, $validTables)) {
            return response()->json(['error' => 'Table not found'], 404);
        }

        try {
            $rows = DB::table($table)->limit(5)->get();
            $columns = Schema::getColumnListing($table);
            
            // Mask password or sensitive fields
            $maskedRows = $rows->map(function ($row) {
                $arr = (array) $row;
                foreach (['password', 'remember_token', 'token', 'secret', 'key'] as $sensitive) {
                    if (isset($arr[$sensitive])) {
                        $arr[$sensitive] = '••••••••';
                    }
                }
                return $arr;
            });

            return response()->json([
                'table' => $table,
                'columns' => $columns,
                'rows' => $maskedRows,
                'count' => DB::table($table)->count()
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Determine module cluster for a table
     */
    protected function determineModule(string $table): array
    {
        foreach ($this->moduleConfig as $key => $config) {
            if (in_array($table, $config['tables'])) {
                return [
                    'key' => $key,
                    'name' => $config['name'],
                    'color' => $config['color'],
                    'badge_bg' => $config['badge_bg']
                ];
            }
        }

        // Fuzzy matches
        if (Str::startsWith($table, ['wo_', 'pm_', 'work_order', 'jwo', 'plan_budget'])) {
            return array_merge(['key' => 'work_order'], $this->moduleConfig['work_order']);
        }
        if (Str::startsWith($table, ['tool_', 'stock_opname', 'incident_'])) {
            return array_merge(['key' => 'toolroom'], $this->moduleConfig['toolroom']);
        }
        if (Str::startsWith($table, ['hse_', 'far'])) {
            return array_merge(['key' => 'hse_safety'], $this->moduleConfig['hse_safety']);
        }
        if (Str::startsWith($table, ['production_'])) {
            return array_merge(['key' => 'production'], $this->moduleConfig['production']);
        }
        if (Str::startsWith($table, ['meeting_'])) {
            return array_merge(['key' => 'meeting_action'], $this->moduleConfig['meeting_action']);
        }
        if (Str::startsWith($table, ['part_'])) {
            return array_merge(['key' => 'inventory_part'], $this->moduleConfig['inventory_part']);
        }
        if (Str::startsWith($table, ['unit_'])) {
            return array_merge(['key' => 'asset_unit'], $this->moduleConfig['asset_unit']);
        }

        return [
            'key' => 'system_core',
            'name' => 'Modul Tambahan / Sistem',
            'color' => '#64748b',
            'badge_bg' => 'rgba(100, 116, 139, 0.2)'
        ];
    }

    /**
     * Format table name into readable title
     */
    protected function formatTableTitle(string $table): string
    {
        return Str::title(str_replace('_', ' ', $table));
    }

    /**
     * Format bytes to readable size
     */
    protected function formatBytes(int $bytes): string
    {
        if ($bytes <= 0) return '0 B';
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes, 1024));
        return round($bytes / pow(1024, $i), 2) . ' ' . $units[$i];
    }
}
