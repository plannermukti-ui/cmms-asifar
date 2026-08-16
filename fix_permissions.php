<?php
$controllers = [
    'JwoController.php' => 'jwos',
    'PlanBudgetController.php' => 'plan_budgets',
    'PmScheduleController.php' => 'pm_schedules',
    'PmTemplateController.php' => 'pm_templates',
    'PraWorkOrderController.php' => 'pra_work_orders',
    'ProductionController.php' => 'productions',
    'SwapComponentController.php' => 'swap_components',
    'VendorController.php' => 'vendors'
];

foreach ($controllers as $file => $module) {
    $path = __DIR__ . '/app/Http/Controllers/' . $file;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        if (strpos($content, "middleware('permission") === false) {
            $constructor = "\n    public function __construct()\n    {\n";
            $constructor .= "        \$this->middleware('permission:view_$module')->only(['index', 'show']);\n";
            $constructor .= "        \$this->middleware('permission:create_$module')->only(['create', 'store']);\n";
            $constructor .= "        \$this->middleware('permission:edit_$module')->only(['edit', 'update']);\n";
            $constructor .= "        \$this->middleware('permission:delete_$module')->only(['destroy']);\n";
            $constructor .= "    }\n";
            
            if (strpos($content, 'function __construct') !== false) {
                $content = preg_replace('/function __construct\(\)\s*\{/', "function __construct()\n    {\n        \$this->middleware('permission:view_$module')->only(['index', 'show']);\n        \$this->middleware('permission:create_$module')->only(['create', 'store']);\n        \$this->middleware('permission:edit_$module')->only(['edit', 'update']);\n        \$this->middleware('permission:delete_$module')->only(['destroy']);", $content);
            } else {
                $pattern = '/class\s+[a-zA-Z0-9_]+\s+extends\s+Controller\s*\{/';
                $content = preg_replace($pattern, "$0$constructor", $content);
            }
            file_put_contents($path, $content);
            echo "Updated $file\n";
        }
    }
}
echo "Done.\n";
