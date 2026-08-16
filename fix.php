<?php
$wos = App\Models\WorkOrder::whereNull('site_id')->get();
foreach($wos as $w) {
    if ($w->unit) {
        $w->site_id = $w->unit->site_id;
        $w->save();
        echo "Fixed WO: " . $w->no_wo . "\n";
    } else {
        echo "WO has no unit: " . $w->no_wo . "\n";
    }
}
